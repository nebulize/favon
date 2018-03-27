const cors = require('@koa/cors');
const ora = require('ora');
const chalk = require('chalk');
const serve = require('webpack-serve');
const config = require('./config');
const webpackConfig = require('./webpack.dev.conf');
const utils = require('./utils');

(async() => {
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
        utils.createHmrFile();
        spinner.stop();
        console.log(chalk.cyan('  Watching for changes.\n'));
        process.on('exit', function() {
          utils.removeHmrFile()
        });
      }
    }
  });
})();
