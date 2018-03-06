const path = require('path');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const StyleLintPlugin = require('stylelint-webpack-plugin');
const eslintFriendlyFormatter = require('eslint-friendly-formatter');

const project = {
  name: 'favon',
};

const extractSass = new ExtractTextPlugin({
  filename: `./css/${project.name}.min.css`,
  // disable: process.env.NODE_ENV === 'development',
});

const config = {
  entry: {
    app: './resources/assets/js/index.js',
    seasonal: './resources/assets/js/seasonal/index.js',
  },
  output: {
    path: path.resolve(__dirname, 'public'),
    filename: `js/${project.name}.[name].bundle.js`,
  },
  module: {
    rules: [{
      test: /\.scss$/,
      use: extractSass.extract({
        use: [{
          loader: 'css-loader',
          options: {
            sourceMap: true,
          },
        }, {
          loader: 'postcss-loader',
          options: {
            sourceMap: true,
          },
        }, {
          loader: 'sass-loader',
          options: {
            sourceMap: true,
          },
        }],
        fallback: 'style-loader',
      }),
    }, {
      test: /\.js$/,
      enforce: 'pre',
      loader: 'eslint-loader',
      exclude: /node_modules/,
      options: {
        formatter: eslintFriendlyFormatter,
      },
    }, {
      test: /\.js$/,
      exclude: /node_modules/,
      use: {
        loader: 'babel-loader',
        options: {
          presets: ['@babel/preset-env'],
        },
      },
    }],
  },
  plugins: [
    extractSass,
    new StyleLintPlugin({
      context: './resources/assets/scss',
      syntax: 'scss',
    }),
  ],
  stats: 'errors-only',
};

module.exports = config;
