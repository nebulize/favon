## Favon


![Maintainability](https://api.codeclimate.com/v1/badges/947a36b5884e29b2f466/maintainability) ![StyleCI](https://github.styleci.io/repos/120039829/shield?branch=develop)

Favon is a website for discovering and tracking TV shows, written in PHP (Laravvel 5.6) and VueJS. 

### Installation
- Clone project
- `cp .env.example .env`
- `composer install`
- `php artisan key:generate`
- Adjust .env values with your database setup and API keys
- `php artisan migrate`
- `php artisan seed --class="Seeds\DatabaseSeeder"`

### Progress

Favon is in an early alpha phase, meaning most features are still missing. Check out our [roadmap](https://github.com/nebulize/favon-roadmap) for a rough sketch of where we're at and what's to come.
