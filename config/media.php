<?php

return [
    'tvdb_api_key' => env('TVDB_API_KEY'),
    'tvdb_user_key' => env('TVDB_USER_KEY'),
    'tvdb_user_name' => env('TVDB_USER_NAME'),
    'tvdb_url' => 'https://api.thetvdb.com',

    'omdb_api_key' => env('OMDB_API_KEY'),
    'omdb_url' => 'http://www.omdbapi.com',

    'tmdb_api_key' => env('TMDB_API_KEY'),
    'tmdb_url' => 'https://api.themoviedb.org/3',

    'image_base_path' => 'http://image.tmdb.org/t/p/',
    'poster_sizes' => ['w92', 'w342'],
    'banner_sizes' => ['w1280'],
    'profile_sizes' => ['w185'],

];
