const imagemin = require('imagemin');
const imageminPngquant = require('imagemin-pngquant');
const imageminSvgo = require('imagemin-svgo');
const imageminMozjpeg = require('imagemin-mozjpeg');
const imageminWebp = require('imagemin-webp');
const fs = require('fs-extra');
const ora = require('ora');
const chalk = require('chalk');
const config = require('./config');

const fontFilter = async (src, dest) => {
  const filter = /\.(woff2?|eot|ttf|otf)(\?.*)?$/;
  if (fs.lstatSync(src).isDirectory()) return true;
  return filter.test(src);
};

const avFilter = (src, dest) => {
  const filter = /\.(mp4|webm|ogg|mp3|wav|flac|aac)(\?.*)?$/;
  if (fs.lstatSync(src).isDirectory()) return true;
  return filter.test(src);
};

const imageFilter = (src, dest) => {
  const filter = /\.(png|jpe?g|gif|svg)(\?.*)?$/;
  if (fs.lstatSync(src).isDirectory()) return true;
  return filter.test(src);
};

exports.optimizeImages = async () => {
  const spinner = ora('Optimizing images...');
  spinner.start();
  await imagemin([`${config.paths.src.root}/${config.paths.src.images}/*.{jpg,jpeg,png,svg,gif}`], `${config.paths.dist.root}/${config.paths.dist.images}`, {
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
    await imagemin([`${config.paths.src.root}/${config.paths.src.images}/*.{jpg,jpeg,png,gif}`], `${config.paths.dist.root}/${config.paths.dist.images}`, {
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

exports.copyAssets = async () => {
  const spinner = ora('Copying static assets...');
  spinner.start();
  try {
    await fs.copy(`${config.paths.src.root}/${config.paths.src.fonts}`, `${config.paths.dist.root}/${config.paths.dist.fonts}`, { filter: fontFilter });
    await fs.copy(`${config.paths.src.root}/${config.paths.src.media}`, `${config.paths.dist.root}/${config.paths.dist.media}`, { filter: avFilter });
    spinner.stop();
    console.log(chalk.cyan('  Copied static assets.'));
  } catch (err) {
    // Source folder does not exist
    if (err.code === 'ENOENT') {
      spinner.stop();
    } else {
      spinner.stop();
      console.log(chalk.red('  Error while copying static assets.\n'));
      console.error(err);
    }
  }
};

exports.copyImages = async() => {
  const spinner = ora('Copying images...');
  spinner.start();
  try {
    await fs.copy(`${config.paths.src.root}/${config.paths.src.images}`, `${config.paths.dist.root}/${config.paths.dist.images}`, { filter: imageFilter });
    spinner.stop();
    console.log(chalk.cyan('  Copied image assets.'));
  } catch (err) {
    // Source folder does not exist
    if (err.code === 'ENOENT') {
      spinner.stop();
    } else {
      spinner.stop();
      console.log(chalk.red('  Error while copying image assets.\n'));
      console.error(err);
    }
  }
};
