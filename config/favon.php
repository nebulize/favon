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

    'image_base_path' => 'https://image.tmdb.org/t/p/',
    'poster_sizes' => ['w92', 'w342'],
    'banner_sizes' => ['w1280'],
    'profile_sizes' => ['w185'],

    /*
    |--------------------------------------------------------------------------
    | TVDB genre mapping
    |--------------------------------------------------------------------------
    |
    | List of all TVDB genres that are of interest to us, and their equivalent
    | name in our own database.
    |
    */
    'tvdb_genres' => [
        'Action' => 'Action',
        'Adventure' => 'Adventure',
        'Animation' => 'Animation',
        'Children' => 'Children',
        'Comedy' => 'Comedy',
        'Crime' => 'Crime',
        'Documentary' => 'Documentary',
        'Drama' => 'Drama',
        'Family' => 'Family',
        'Fantasy' => 'Fantasy',
        'Food' => 'Food',
        'Game Show' => 'Game-Show',
        'Home and Garden' => 'Reality-TV',
        'Horror' => 'Horror',
        'News' => 'News',
        'Reality' => 'Reality-TV',
        'Science Fiction' => 'Sci-Fi',
        'Sport' => 'Sport',
        'Suspense' => 'Thriller',
        'Talk Show' => 'Talk-Show',
        'Thriller' => 'Thriller',
        'Travel' => 'Documentary',
        'Western' => 'Western',
    ],

    /*
    |--------------------------------------------------------------------------
    | TMDB genre mapping
    |--------------------------------------------------------------------------
    |
    | List of all TMDB TV show genres that are of interest to us, and their
    | equivalent name in our own database. We're not including some ambiguous
    | ones like `Sci-Fi & Fantasy` since we store those separately in our
    | database. In those cases we'll hopefully fall back to the TVDB or OMDB
    | genres where these genres are separated.
    |
    */
    'tmdb_genres' => [
        'Animation' => 'Animation',
        'Comedy' => 'Comedy',
        'Crime' => 'Crime',
        'Documentary' => 'Documentary',
        'Drama' => 'Drama',
        'Family' => 'Family',
        'Kids' => 'Children',
        'Mystery' => 'Mystery',
        'News' => 'News',
        'Talk' => 'Talk-Show',
        'Western' => 'Western'
    ],

    /*
    |--------------------------------------------------------------------------
    | OMDB genre mapping
    |--------------------------------------------------------------------------
    |
    | List of all OMDB TV show genres that are of interest to us, and their
    | equivalent name in our own database.
    |
    */
    'omdb_genres' => [
        'Action' => 'Action',
        'Adventure' => 'Adventure',
        'Animation' => 'Animation',
        'Biography' => 'Biography',
        'Comedy' => 'Comedy',
        'Crime' => 'Crime',
        'Documentary' => 'Documentary',
        'Drama' => 'Drama',
        'Family' => 'Family',
        'Fantasy' => 'Fantasy',
        'Game-Show' => 'Game-Show',
        'History' => 'History',
        'Horror' => 'Horror',
        'Music' => 'Music',
        'Musical' => 'Music',
        'Mystery' => 'Mystery',
        'News' => 'News',
        'Reality-TV' => 'Reality-TV',
        'Romance' => 'Romance',
        'Sci-Fi' => 'Sci-Fi',
        'Sport' => 'Sport',
        'Talk-Show' => 'Talk-Show',
        'Thriller' => 'Thriller',
        'Western' => 'Western',
    ],

];
