### Commands\Cronjobs

These commands are cronjobs used to keep the database up to date.

`favon:update:tv:ratings` - Synchronizes the average ratings and member counts for all tv seasons. This is mainly used to improve the efficiency for the seasonal overview database queries, since otherwise we would have to call `AVG` and `COUNT` on potentially thousands or even hundreds of thousand rows for each tv season (200+).

`favon:update:tv:counts` - Synchronizes the number of episodes for each tv season. Same reason as above. It's redundant information since now we store the number of episodes in a separate field in the database when we could just query the `tv_episodes` table dynamically, but again it improves performance when doing it for hundreds of shows at once.

`favon:update:tv:imdb` - Fetches the most recent ratings export from IMDB, parses it line by line and updates the IMDB score in our database in case that shows or movie is found in our database. Quite memory intensive unfortunately.

`favon:update:persons {start?} {end?}` - Fetches all updated persons from the last 24 hours (or within the specified timeframe) from TMDB and updates them in our database.

`favon:update:tv:popularity` - For now we don't have our own popularity value, so we just use the one from TMDB. This fetches the current popularity values for all tv shows from the current season.

`favon:update:shows {start?} {end?}` - Fetches all updated tv shows from the last 24 hours (or within the specified timeframe) from TMDB and updates them in our database.
