/* Builds the theme and writes a deployable zip next to the theme folder.
 *
 * Ships everything WordPress needs at runtime plus the sources, and leaves out
 * node_modules and the build scratch directory. assets/dist is deliberately
 * included: it is gitignored, but the theme has no styles or scripts without it
 * and the server is not expected to run npm.
 */
import { execFile } from 'node:child_process';
import { mkdtemp, rm, cp, readFile } from 'node:fs/promises';
import { tmpdir } from 'node:os';
import path from 'node:path';
import { promisify } from 'node:util';
import { fileURLToPath } from 'node:url';

const run = promisify(execFile);
const ROOT = path.dirname(fileURLToPath(import.meta.url));
const SLUG = path.basename(ROOT);

const SKIP = new Set(['node_modules', '.build', 'package-lock.json', '.gitignore']);

const { version } = JSON.parse(await readFile(path.join(ROOT, 'package.json'), 'utf8'));
const output = path.join(ROOT, '..', `${SLUG}-${version}.zip`);

await run('node', [path.join(ROOT, 'build.mjs')]);

const staging = await mkdtemp(path.join(tmpdir(), 'severus-'));
const target = path.join(staging, SLUG);

await cp(ROOT, target, {
  recursive: true,
  filter: source => {
    const relative = path.relative(ROOT, source);
    if (!relative) return true;
    return !SKIP.has(relative.split(path.sep)[0]);
  },
});

await rm(output, { force: true });
await run('zip', ['-rq', output, SLUG], { cwd: staging });
await rm(staging, { recursive: true, force: true });

const { stdout } = await run('du', ['-h', output]);
console.log(`packaged ${stdout.trim()}`);
