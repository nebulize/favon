const path = require('path');

let plugins = {
  stylelint: {},
  'postcss-import': {},
  tailwindcss: {},
  'postcss-nested': {},
  'postcss-preset-env': {},
  'postcss-reporter': {},
};

if (process.env.NODE_ENV === 'production') {
  plugins = {
    ...plugins,
    '@fullhuman/postcss-purgecss': {
      content: [
        path.resolve(__dirname, 'resources/views/**/*.blade.php'),
        path.resolve(__dirname, 'app/*/Resources/views/**/*.blade.php'),
        path.resolve(__dirname, 'app/*/Resources/assets/js/**/*.vue'),
        path.resolve(__dirname, 'app/*/Resources/assets/js/**/*.ts'),
      ],
      defaultExtractor(content) {
        return content.match(/[\w-/:]+(?<!:)/g) || [];
      },
      whitelistPatterns: [],
      whitelist: [],
    },
  };
}

module.exports = {
  plugins,
};
