<br />
<div align="center">
  <p align="center">
    <a href="https://php.net/" target="_blank"><img src="https://img.shields.io/badge/php-%3E%3D%207.2-8892BF.svg"></a>
    <a href="#quality-assurance" target="_blank"><img src="https://img.shields.io/badge/qa--level-high-success"></a>
  </p>

  <strong>
    <h2 align="center">Favon</h2>
  </strong>

  <p align="center">
    📺 Website for discovering and tracking TV shows.
  </p>

  <p align="center">
    <strong>
    <a href="#index">index</a>
    &nbsp; &middot; &nbsp;
    <a href="documentation/README.md">documentation</a>
    </strong>
  </p>

  <br>

  <p align="center">
    <a href="https://laravel.com/">
      <img src="https://www.vectorlogo.zone/logos/laravel/laravel-icon.svg" height="45" />
    </a>
    &nbsp;
    <a href="https://www.typescriptlang.org/">
      <img src="https://www.vectorlogo.zone/logos/typescriptlang/typescriptlang-icon.svg" height="45" />
    </a>
    &nbsp;
    <a href="https://postcss.org/">
      <img src="https://www.vectorlogo.zone/logos/postcss/postcss-icon.svg" height="45" />
    </a>
    &nbsp;
    <a href="https://tailwindcss.com/">
      <img src="https://www.vectorlogo.zone/logos/tailwindcss/tailwindcss-icon.svg" height="45" />
    </a>
    &nbsp;
    <a href="https://webpack.js.org/">
      <img src="https://www.vectorlogo.zone/logos/js_webpack/js_webpack-icon.svg" height="45" />
    </a>
  </p>
</div>
<br />

## Index

<pre>
<a href="#installation"
>> Installation ..................................................................... </a>
<a href="#compiling-frontend-assets"
>> Compiling Frontend Assets ........................................................ </a>
<a href="#seeding"
>> Seeding .......................................................................... </a>
<a href="#structure"
>> Structure ........................................................................ </a>
<a href="#linting"
>> Linting .......................................................................... </a>
<a href="#pre-commit"
>> Pre-Commit ....................................................................... </a>
<a href="#tests"
>> Tests ............................................................................ </a>
<a href="#polyfills"
>> Polyfills ........................................................................ </a>
<a href="#quality-assurance"
>> Quality Assurance ................................................................ </a>
<a href="#documentation"
>> Documentation .................................................................... </a>
</pre>

## Installation

Please read the [Contributing](CONTRIBUTING.md) guideline before you create any
pull requests.

```bash
git clone git@github.com:nebulize/favon.git
cp .env.example .env # Make sure the variables are correct
cp .env.dusk.example .env.dusk.local
just --list # Check out available tasks
just backend # From Homestead
just frontend # From System / IDE
just assets # Compile frontend assets
```

<details>
  <summary><strong>About <code>just</code></strong></summary>

<hr>
[Just](https://github.com/casey/just) is a command runner similar to <code>make</code> with some advantages 
and better cross-platform support. It should be installed both in Homestead and on your local system.
You can list all available commands in a project using <code>just --list</code>.
<br><br>

**Installation Ubuntu / WSL / Homestead**:  

```bash
curl --proto '=https' --tlsv1.2 -sSf https://just.systems/install.sh | sudo bash -s -- --to /usr/local/bin
sudo chown $USER:$USER /usr/local/bin/just
```
**Installation Windows (Git Bash)**:  

```bash
# Download latest release from https://github.com/casey/just/releases
# Extract and copy just.exe to C:\Program Files\Git\mingw64\bin
# You can now use `just` from Git Bash
```
**Installation Mac**:  

```bash
brew install just
```
</details>

## Compiling Frontend Assets

```bash
just assets # For development (or use `yarn watch`)
just assets prod # For production
```

## Seeding

To seed the local database with some usable data, run:

```bash
php artisan module:seed
```

## Structure

This project is based on a DDD structure, using
[`laravel-modules`](https://nwidart.com/laravel-modules/v4/introduction):

```bash
app/
├── Account # Everything regarding users and their accounts (including authentication)
├── Application # Application bootstrapping
├── Common # Common interfaces and classes used by multiple modules
├── Television # Application and presentation logic for television shows 
```

For more information check out the
[technical documentation](documentation/README.md).

## Linting

To lint (and fix) your PHP code, run the following command:

```bash
just lint
```

Make sure your code passes before pushing, since otherwise the build will fail
and your pull request won't be merged.

## Pre-Commit

Since linting the files manually before each commit is cumbersome, a
`pre-commit` configuration is available to run PHP CS Fixer before each commit.
If you have used `pre-commit` before, then all you need to do is run
`pre-commit install` once after cloning the project, and you're set. Otherwise,
[install `pre-commit`](https://pre-commit.com/#install).

<details>
  <summary>Workflow</summary>

```bash
git add .
git commit -m "Commit message"
# If fixes are done, stage and commit again:
git add -u && !!
```

</details>

## Tests

Run the tests with `just test`. This will run both unit and integration tests
for all modules. A code coverage report can be generated with
`just coverage`. This will take **significantly** longer than just running
the tests normally.

## Polyfills
The following Polyfills are loaded from `polyfill.io` (a service that only returns those polyfills that the requesting browser actually requires):

```

```

When using any additional, non-transpilable ES6 features, make sure to update this documentation and the `polyfills.io` link under `app/Common/Resources/views/layouts/master.blade.php`

## Quality Assurance

A list of QA criteria has been defined for this project. When contributing, try
to keep these goals in mind.

<details>
  <summary>🟢 QA Criteria</summary>

-   [x] Domain Driven Design
-   [x] Linting Configuration
-   [x] Code Quality Configuration (Larastan)
-   [x] Pre-Commit Configuration
-   [x] CI Configuration: [Build, Lint, Test, Quality]
-   [ ] Continuous Deployment
-   [x] JUSTFILE
-   [x] Documentation
-   [ ] > 95% Test Coverage
-   [x] Logging: Sentry
-   [ ] Secure Headers
</details>

## Documentation

The technical documentation for this project can be found
[here](documentation/README.md).
