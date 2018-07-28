### Commands\Initialize

These commands are used to initialize the database and fetch all base data (mostly from TMDB). They should be executed once after the site has been set up. After that, we only fetch the changes from the last 24 hours (daily).

`favon:fetch:countries` - Fetches all countries from TMDB

`favon:fetch:languages` - Fetches all languages from TMDB

`favon:fetch:persons` - Fetches all persons from TMDB. This will take a long time (upwards of 100 hours). The command itself doesn't take that long, it just fetches the newest data export from TMDB, and dispatches a job for each entry (1M+ jobs). The processing of the jobs will take a long time though. You will need a queue system with enough memory (Redis on a 2GB memory machine crapped out at 200k jobs for me).
