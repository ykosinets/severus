/* Shared PostCSS pipeline. `@import` pulls the component sheets together,
   `postcss-nesting` compiles the nested syntax the components are written in. */
import postcssImport from 'postcss-import';
import postcssNesting from 'postcss-nesting';
import autoprefixer from 'autoprefixer';
import cssnano from 'cssnano';

export default ({ minify = false } = {}) => [
  postcssImport(),
  postcssNesting({ edition: '2024-02' }),
  autoprefixer(),
  ...(minify ? [cssnano({ preset: ['default', { calc: false, colormin: false }] })] : []),
];
