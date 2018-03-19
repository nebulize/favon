const path = require('path');

module.exports = {
  // Project name
  name: 'favon',

  // Source and dist paths for assets
  paths: {
    src: {
      root: path.resolve(__dirname, '../resources/assets'),
      entry: path.resolve(__dirname, '../resources/assets/js/index.js'),

      // Paths for asset folders, relative to the root directory
      images: 'images',
      media: 'media', // Audio or video files
      js: 'js',
      sass: 'scss',
      fonts: 'fonts'
    },
    dist: {
      root: path.resolve(__dirname, '../public'),
      // Paths for asset folders, relative to the root directory
      images: 'images',
      media: 'media', // Audio or video files
      js: 'js',
      css: 'css',
      fonts: 'fonts'
    }
  },

  // Development config
  dev: {
    assetsPublicPath: '/',
    // If true, eslint errors and warnings will also be shown in the error overlay in the browser.
    showEslintErrorsInOverlay: true,
    cacheBusting: true,
    hmrHost: 'localhost',
    hmrPort: 8080,
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
