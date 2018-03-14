const webpack = require('webpack');
const StyleLintPlugin = require('stylelint-webpack-plugin');
const config = require('./config');
const vueLoaderConfig = require('./vue-loader.conf');

module.exports = {
  entry: {
    app: `${config.paths.src.entry}`,
    seasonal: './resources/assets/js/seasonal/index.js',
  },
  output: {
    path: config.paths.dist.root,
    filename: `${config.paths.dist.js}/${config.name}.[name].js`,
    chunkFilename: `${config.paths.dist.js}/${config.name}.[name].js`,
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
            name: `${config.paths.dist.images}/[name].[ext]`
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
      {
        test: /\.(mp4|webm|ogg|mp3|wav|flac|aac)(\?.*)?$/,
        loader: 'url-loader',
        options: {
          limit: 10000,
          name: `${config.paths.dist.media}/[name].[ext]`
        }
      },
      {
        test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
        loader: 'url-loader',
        options: {
          limit: 10000,
          name: `${config.paths.dist.fonts}/[name].[ext]`
        }
      },
    ],
  },
  plugins: [
    // new StyleLintPlugin({
    //   context: './resources/assets/scss',
    //   syntax: 'scss',
    // }),
  ],
  stats: {
    colors: true,
    modules: false,
    children: false,
    chunks: false,
    chunkModules: false
  }
};
