process.env.NODE_ENV = 'production';

const ora = require('ora');
const chalk = require('chalk');
const webpack = require('webpack');
const rm = require('rimraf');
const utils = require('./utils');
const config = require('./config');
const webpackConfig = require('./webpack.prod.conf');

const spinner = ora('Building for production...\n');
spinner.start();

rm(`${config.paths.dist.root}/{images,css,js}`, err => {
  if (err) throw err;
  utils.optimizeImages().then(() => {
    webpack(webpackConfig, (err, stats) => {
      spinner.stop();
      if (err) throw err;
      process.stdout.write(stats.toString({
        colors: true,
        modules: false,
        children: false,
        chunks: false,
        chunkModules: false
      }) + '\n\n');

      if (stats.hasErrors()) {
        console.log(chalk.red('  Build failed with errors.\n'));
        process.exit(1);
      }

      console.log(chalk.cyan('  Build complete.\n'));
    })
  });
});
