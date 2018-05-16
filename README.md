## Favon

Favon is a website for discovering and tracking TV shows. If you know MAL, I'm planning to do something similar, just for TV shows (and possibly movies) instead. I was not satisfied with other sites such as Trakt, so I decided to build my own.

Favon is (for now) developed in PHP (Laravel 5.6) and VueJS. In the future I'd love to rewrite it in Elixir / Phoenix, but for now I'm more comfortable with Laravel.

### Installation

Typical Laravel Installation:

- Clone project
- `cp .env.example .env`
- `composer install`
- `php artisan key:generate`
- Adjust .env values with your database setup and API keys
- `php artisan migrate --seed`

Contact me if you need a DB dump of tv shows as a starting point.



### Progress

Favon is in an early alpha phase, meaning most features are still missing.

- [x] Backend: Fetch and automatically update TV show data from TMDB / OMDB / TVDB / IMDB (mostly)
- [x] Frontend: Seasonal Overview (mostly)
- [x] Authentication (mostly)
- [ ] TV Show Details Page
- [ ] TV Season Details Page
- [ ] Person Details Page
- [ ] Top Rated TV Shows with Filters
- [ ] User Profiles
- [ ] User Lists
- [ ] User Messages
- [ ] User Friends and other social stuff
- [ ] Calendar with airing tv shows
- [ ] Front Page