const imagemin = require('imagemin');
const imageminPngquant = require('imagemin-pngquant');
const imageminSvgo = require('imagemin-svgo');
const imageminMozjpeg = require('imagemin-mozjpeg');
const imageminWebp = require('imagemin-webp');
const ora = require('ora');
const chalk = require('chalk');
const config = require('./config');

exports.optimizeImages = async () => {
  const spinner = ora('Optimizing images...');
  spinner.start();
  await imagemin([`${config.paths.src.images}/*.{jpg,jpeg,png,svg,gif}`], `${config.paths.dist.root}/images`, {
    use: [
      imageminPngquant({
        speed: 1
      }),
      imageminSvgo({
        plugins: [
          {
            removeViewBox: false
          }
        ]
      }),
      imageminMozjpeg({
        progressive: true,
        quality: 80
      })
    ]
  });

  // Create webp files if enabled
  if (config.build.createWebp === true) {
    await imagemin([`${config.paths.src.images}/*.{jpg,jpeg,png,gif}`], config.paths.dist.images, {
      use: [
        imageminWebp({
          quality: 75
        })
      ]
    });
  }

  spinner.stop();
  console.log(chalk.cyan('  Copied and optimized images.'));
};
