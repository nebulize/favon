const path = require('path');
const pkg = require('../package');

module.exports = {
  // Project name
  name: pkg.name || 'favon',

  // Source and dist paths for assets
  paths: {
    src: {
      root: path.resolve(__dirname, '..resources/assets'),
      images: path.resolve(__dirname, '../resources/assets/images'),
    },
    dist: {
      root: path.resolve(__dirname, '../public'),
    }
  },

  // Development config
  dev: {
    assetsPublicPath: '/',
    eslint: true,
    // If true, eslint errors and warnings will also be shown in the error overlay
    // in the browser.
    showEslintErrorsInOverlay: true,

    cacheBusting: true,
  },

  // Build config
  build: {
    // Set to false if you do not wish for webp image files to be created.
    // If set to true, we will create both an optimized car.png and car.webp from a card.png source file
    // https://developers.google.com/speed/webp/
    createWebp: true,

    // Path from which files will be served over HTTP
    assetsPublicPath: '/',
  },
};
