process.env.NODE_ENV = 'development';

const ora = require('ora');
const chalk = require('chalk');
const serve = require('webpack-serve');
const rmfr = require('rmfr');
const path = require('path');
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

  // // Start webpack build
  // console.log(chalk.cyan('  Building for development...'));
  // serve({
  //   config: webpackConfig,
  //   content: path.join(__dirname, '../public'),
  //   host: 'localhost',
  //   port: 8080,
  //   hot: {
  //     hot: true,
  //     host: 'localhost',
  //     port: 8080
  //   },
  //   on: {
  //     listening: () => {
  //       console.log('Watching for changes.');
  //     }
  //   }
  // });
})();
