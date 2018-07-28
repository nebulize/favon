### Commands\Initialize

These commands are used to initialize the database and fetch all base data (mostly from TMDB). They should be executed once after the site has been set up. After that, we only fetch the changes from the last 24 hours (daily).

`favon:fetch:shows` - Fetches all tv shows and their related data (seasons, credits, videos, etc.) from TMDB and other APIs. Just like the command above, this will take a long time. Should be ~75k jobs in this case.
