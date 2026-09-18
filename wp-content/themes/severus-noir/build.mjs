/* Builds the theme's assets out of the component folders.
 *
 *   components/<name>/<name>.pcss  ->  bundled into assets/dist/main.css
 *   components/<name>/<name>.js    ->  bundled into assets/dist/main.js
 *
 * Nothing is registered by hand: the folders are the manifest. Add a component
 * directory and it is picked up on the next `npm run build`.
 */
import { mkdir, readdir, rm, writeFile } from 'node:fs/promises';
import { existsSync } from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import postcss from 'postcss';
import { readFile } from 'node:fs/promises';
import esbuild from 'esbuild';
import makePlugins from './postcss.config.js';

const ROOT = path.dirname(fileURLToPath(import.meta.url));
const WATCH = process.argv.includes('--watch');
const MINIFY = !WATCH;

const BUILD = path.join(ROOT, '.build');
const DIST = path.join(ROOT, 'assets/dist');

/* Globals come first and in this order — tokens before anything that uses them. */
const GLOBAL_STYLES = [
  'src/styles/global/tokens.pcss',
  'src/styles/global/reset.pcss',
  'src/styles/global/typography.pcss',
  'src/styles/global/layout.pcss',
  'src/styles/global/motion.pcss',
];

const rel = file => path.relative(BUILD, path.join(ROOT, file)).split(path.sep).join('/');

async function components(extension) {
  const dir = path.join(ROOT, 'components');
  const names = (await readdir(dir, { withFileTypes: true }))
    .filter(entry => entry.isDirectory())
    .map(entry => entry.name)
    .sort();

  return names
    .map(name => `components/${name}/${name}.${extension}`)
    .filter(file => existsSync(path.join(ROOT, file)));
}

async function buildStyles() {
  const sheets = [...GLOBAL_STYLES.filter(f => existsSync(path.join(ROOT, f))), ...(await components('pcss'))];
  const entry = path.join(BUILD, 'main.pcss');
  await writeFile(entry, sheets.map(file => `@import "${rel(file)}";`).join('\n') + '\n', 'utf8');

  const css = await readFile(entry, 'utf8');
  const result = await postcss(makePlugins({ minify: MINIFY })).process(css, {
    from: entry,
    to: path.join(DIST, 'main.css'),
  });

  await writeFile(path.join(DIST, 'main.css'), result.css, 'utf8');
  return sheets.length;
}

async function buildScripts() {
  const modules = await components('js');
  const entry = path.join(BUILD, 'main.js');
  const body = [
    `import ${JSON.stringify(rel('src/scripts/main.js'))};`,
    ...modules.map(file => `import ${JSON.stringify(rel(file))};`),
  ].join('\n');
  await writeFile(entry, body + '\n', 'utf8');

  await esbuild.build({
    entryPoints: [entry],
    outdir: DIST,
    bundle: true,
    format: 'esm',
    splitting: true,          // keeps three/gsap out of the main file
    target: ['es2022'],
    minify: MINIFY,
    sourcemap: !MINIFY,
    logLevel: 'silent',
    entryNames: 'main',
    chunkNames: 'chunks/[name]-[hash]',
  });

  return modules.length;
}

async function run() {
  // Wipe dist first: esbuild leaves orphaned chunks behind when a dynamic
  // import goes away, and they would still be served.
  await rm(DIST, { recursive: true, force: true });
  await mkdir(BUILD, { recursive: true });
  await mkdir(DIST, { recursive: true });

  const started = Date.now();
  const [sheets, scripts] = await Promise.all([buildStyles(), buildScripts()]);
  console.log(`built ${sheets} stylesheets and ${scripts} component scripts in ${Date.now() - started}ms`);
}

async function watch() {
  const { default: chokidarModule } = await import('node:fs');
  const watched = [path.join(ROOT, 'components'), path.join(ROOT, 'src')];
  let queued = null;

  for (const dir of watched) {
    chokidarModule.watch(dir, { recursive: true }, () => {
      clearTimeout(queued);
      queued = setTimeout(() => run().catch(report), 80);
    });
  }
  console.log('watching components/ and src/ …');
}

const report = error => {
  console.error(error.message || error);
  if (!WATCH) process.exitCode = 1;
};

await rm(BUILD, { recursive: true, force: true });
await run().catch(report);
if (WATCH) await watch();
