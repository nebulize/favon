const path = require('path');

module.exports = {
  // Project name
  name: 'favon',

  // Source and dist paths for assets
  paths: {
    root: path.resolve(__dirname, '../'),
    src: {
      root: path.resolve(__dirname, '../resources/assets'),
      entry: path.resolve(__dirname, '../resources/assets/js/index.js'),

      // Source folder images
      images: path.resolve(__dirname, '../resources/assets/images'),
      // Source folder media (audio, video)
      media: path.resolve(__dirname, '../resources/assets/images'),
      // Source folder JS
      js: path.resolve(__dirname, '../resources/assets/js'),
      // Source folder SASS/SCSS
      sass: path.resolve(__dirname, '../resources/assets/scss'),
      // Source folder fonts
      fonts: path.resolve(__dirname, '../resources/assets/fonts'),
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
    cacheBusting: true,
    hmrHost: 'localhost',
    hmrPort: 3000,
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
