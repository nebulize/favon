const merge = require('webpack-merge');
const webpack = require('webpack');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const WriteFilePlugin = require('write-file-webpack-plugin');
const baseWebpackConfig = require('./webpack.base.conf');
const config = require('./config');

module.exports = merge(baseWebpackConfig, {
  output: {
    path: config.paths.dist.root,
    publicPath: `http://${config.dev.hmrHost}:${config.dev.hmrPort}/`
  },
  mode: 'development',
  module: {
    rules: [
      {
        test: /\.scss$/,
        use: [{
          loader: 'style-loader'
        },{
          loader: 'css-loader',
        }, {
          loader: 'postcss-loader',
        }, {
          loader: 'sass-loader',
        }],
      },
    ]
  },
  devtool: 'cheap-module-eval-source-map',
  plugins: [
    // http://vuejs.github.io/vue-loader/en/workflow/production.html
    new webpack.DefinePlugin({
      'process.env': {
        NODE_ENV: '"development"'
      }
    }),
    new webpack.NamedModulesPlugin(), // HMR shows correct file names in console on update.
    new webpack.NoEmitOnErrorsPlugin(),
    new WriteFilePlugin({
      test: /\.(png|jpe?g|gif|svg|mp4|webm|ogg|mp3|wav|flac|aac|woff2?|eot|ttf|otf)(\?.*)?$/,
    }),
    // Update with your individual paths, we have to use context here or otherwise it will break
    // https://github.com/webpack-contrib/copy-webpack-plugin/issues/141
    new CopyWebpackPlugin([
      { context: `${config.paths.src.root}/images/`, from: '**/*.{jpeg,jpg,png,gif,svg}', to: config.paths.dist.images }
    ]),
  ],
});
