const webpack = require('webpack');
const StyleLintPlugin = require('stylelint-webpack-plugin');
const config = require('./config');
const vueLoaderConfig = require('./vue-loader.conf');

module.exports = {
  entry: {
    app: './resources/assets/js/index.js',
    seasonal: './resources/assets/js/seasonal/index.js',
  },
  output: {
    path: config.paths.dist.root,
    filename: `js/${config.name}.[name].js`,
    chunkFilename: `js/${config.name}.[name].js`,
    publicPath: process.env.NODE_ENV === 'production'
      ? config.build.assetsPublicPath
      : config.dev.assetsPublicPath,
  },
  resolve: {
    extensions: ['.js', '.vue', '.json'],
    alias: {
      'vue$': 'vue/dist/vue.esm.js',
    },
  },
  module: {
    rules: [
      {
        test: /\.(js|vue)$/,
        enforce: 'pre',
        loader: 'eslint-loader',
        exclude: /node_modules/,
        options: {
          formatter: require('eslint-friendly-formatter'),
          emitWarning: !config.dev.showEslintErrorsInOverlay
        },
      },
      {
        test: /\.vue$/,
        loader: 'vue-loader',
        options: vueLoaderConfig
      },
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env'],
          },
        },
      },
      {
        test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
        use: [{
          loader: 'url-loader',
          options: {
            limit: 8192,
            name: 'images/[name].[ext]'
          }
        }, {
          loader: 'image-webpack-loader',
          options: {
            mozjpeg: {
              progressive: true,
              quality: 80
            },
            optipng: {
              enabled: false,
            },
            pngquant: {
              quality: '70-90',
              speed: 4
            },
            svgo: {},
            webp: {
              quality: 75
            }
          }
        }]
      },
    ],
  },
  plugins: [
    new StyleLintPlugin({
      context: './resources/assets/scss',
      syntax: 'scss',
    }),
    new webpack.LoaderOptionsPlugin({ options: {} }),
  ],
  stats: 'errors-only',
};
