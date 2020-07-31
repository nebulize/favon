const mix = require('laravel-mix');
const path = require('path');
const fs = require('fs');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

const config = {
  distPath: 'public',
  css: 'main.pcss',
  js: 'index.js',
};

const modules = {
  account: 'app/Account/Resources/assets',
  television: 'app/Television/Resources/assets',
};

const source = {
  images: 'images',
  scripts: 'js',
  styles: 'css',
  static: 'static',
};

/**
 * 🔧 Base Configuration
 */
mix.disableNotifications();
mix.webpackConfig({
  resolve: { alias: source },
  plugins: mix.inProduction()
    ? [
      new CleanWebpackPlugin({
        cleanOnceBeforeBuildPatterns: [
          'css',
          'js',
          'images',
          'fonts',
          'static',
        ],
      }),
    ]
    : [],
});

/**
 * 🎨 Styles
 * Using post-css with some default plugins
 * https://laravel-mix.com/docs/4.0/css-preprocessors
 * https://postcss.org/
 * @see postcss.config.js
 */
require('laravel-mix-postcss-config');

Object.keys(modules).forEach(module => {
  if (fs.existsSync(path.join(modules[module], source.styles, config.css))) {
    mix.postCss(
      path.join(modules[module], source.styles, config.css),
      path.join(config.distPath, source.styles, `${module}.css`)
    );
  }
});

mix.options({ processCssUrls: false }).postCssConfig();

/**
 * 📜 Scripts
 * Transpile ES6 to vanilla javascript
 * Lint code using ESLint
 * Automatically add needed polyfills depending on the supported browsers
 * Extract vendor code into separate bundle
 * https://laravel-mix.com/docs/4.0/css-preprocessors
 * https://postcss.org/
 * @see postcss.config.js
 */
require('laravel-mix-eslint');
Object.keys(modules).forEach(module => {
  if (fs.existsSync(path.join(modules[module], source.scripts, config.js))) {
    mix.js(
      path.join(modules[module], source.scripts, config.js),
      path.join(config.distPath, source.scripts, `${module}.js`)
    );
  }
});
mix.eslint();

/**
 * 🖼️ Images
 * Optimize and copy images
 */
require('laravel-mix-imagemin');
const folders = [];
Object.keys(modules).forEach(module => {
  if (fs.existsSync(path.join(modules[module], source.images))) {
    folders.push({
      from: '**/*',
      to: path.join('images', module),
      context: path.join(modules[module], source.images),
    });
  }
});
mix.imagemin(folders);

/**
 * 🗂️ Static Files
 * Copy additional, static assets without any transformations
 */
Object.keys(modules).forEach(module => {
  mix.copyDirectory(
    path.join(modules[module], source.static),
    path.join(config.distPath, source.static, module)
  );
});

/**
 * 📣 Versioning
 * Add file hashes to all assets for cache-busting
 * Converts the query-based versioning of laravel-mix to filename-based versioning:
 * main.css?id=abcd1234 => main.abcd1234.css
 */
if (mix.inProduction()) {
  mix.version();
}
