process.env.NODE_ENV = 'development';

const cors = require('@koa/cors');
const ora = require('ora');
const chalk = require('chalk');
const serve = require('webpack-serve');
const rmfr = require('rmfr');
const utils = require('./utils');
const config = require('./config');
const webpackConfig = require('./webpack.dev.conf');

(async() => {
  // Clear asset directories
  await rmfr(`${config.paths.dist.root}/{images,css,js,fonts}`);
  // Copy image assets
  await utils.copyImages();
  // Copy static assets (fonts, videos, audio files)
  await utils.copyAssets();

  // Start webpack build
  const spinner = ora('Building for development...');
  serve({
    add: (app, middleware, options) => {
      app.use(cors());
    },
    config: webpackConfig,
    content: config.paths.dist.root,
    host: config.dev.hmrHost,
    port: config.dev.hmrPort,
    on: {
      listening: () => {
        spinner.stop();
        console.log(chalk.cyan('  Watching for changes.\n'));
      }
    }
  });
})();
