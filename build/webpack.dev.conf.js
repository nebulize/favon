const merge = require('webpack-merge');
const webpack = require('webpack');
const path = require('path');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const baseWebpackConfig = require('./webpack.base.conf');
const config = require('./config');

const extractSass = new ExtractTextPlugin({
  filename: `./${config.paths.dist.css}/${config.name}.min.css`,
});

module.exports = merge(baseWebpackConfig, {
  output: {
    publicPath: 'http://localhost:8080'
  },
  mode: 'development',
  devServer: {
    hot: true, // this enables hot reload
    inline: true, // use inline method for hmr
    host: 'localhost',
    port: 3000,
    contentBase: path.join(__dirname, '../public'), // should point to the laravel public folder
    watchOptions: {
      poll: false // needed for homestead/vagrant setup
    }
  },
  module: {
    rules: [
      {
        test: /\.scss$/,
        use: extractSass.extract({
          use: [{
            loader: 'css-loader',
            options: {
              sourceMap: true
            }
          }, {
            loader: 'postcss-loader',
            options: {
              sourceMap: true
            }
          }, {
            loader: 'sass-loader',
            options: {
              sourceMap: true
            }
          }],
          fallback: 'style-loader',
        }),
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
    // Extract css into its own file
    extractSass,
    new webpack.HotModuleReplacementPlugin(),
    new webpack.NamedModulesPlugin(), // HMR shows correct file names in console on update.
    new webpack.NoEmitOnErrorsPlugin(),
  ],
  optimization: {
    splitChunks: {
      cacheGroups: {
        commons: {
          test: /[\\/]node_modules[\\/]/,
          name: "vendor",
          chunks: "all",
        },
      },
    },
  }
});
