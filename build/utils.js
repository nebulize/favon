const imagemin = require('image-2-min');
const imageminPngquant = require('imagemin-pngquant');
const imageminSvgo = require('imagemin-svgo');
const imageminMozjpeg = require('imagemin-mozjpeg');
const imageminWebp = require('imagemin-webp');
const ora = require('ora');
const fs = require('fs-extra');
const path = require('path');
const chalk = require('chalk');
const config = require('./config');

function renameFiles(files, extension) {
  files.forEach((file) => {
    const name = path.basename(file.path, path.extname(file.path));
    const dir = path.dirname(file.path);
    fs.renameSync(file.path, `${dir}/${name}${extension}.webp`);
  });
}

exports.optimizeImages = async () => {
  const spinner = ora('Optimizing images...');
  spinner.start();
  const images = await imagemin([`**/*.{jpg,jpeg,png,svg,gif}`], `${config.paths.dist.root}/${config.paths.dist.images}`, {
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
    ],
    cwd: config.paths.src.images
  });

  // Create webp files if enabled
  if (config.build.createWebp === true) {
    const files1 = await imagemin([`**/*.jpg`], `${config.paths.dist.root}/${config.paths.dist.images}`, {
      use: [
        imageminWebp({
          quality: 75
        })
      ],
      cwd: config.paths.src.images
    });
    renameFiles(files1, 'jpg');
    const files2 = await imagemin([`**/*.png`], `${config.paths.dist.root}/${config.paths.dist.images}`, {
      use: [
        imageminWebp({
          lossless: true
        })
      ],
      cwd: config.paths.src.images
    });
    renameFiles(files2, 'png');
    const files = files1.concat(files2);
    spinner.stop();
    console.log(chalk.greenBright(`  Created ${files.length} webp images.`));
  }

  spinner.stop();
  console.log(chalk.cyan(`  Copied and optimized ${images.length} images.`));
};

exports.createHmrFile = () => {
  fs.writeFileSync(`${config.paths.dist.root}/hmr`, `http://${config.dev.hmrHost}:${config.dev.hmrPort}`)
};

exports.removeHmrFile = () => {
  fs.removeSync(`${config.paths.dist.root}/hmr`);
};
