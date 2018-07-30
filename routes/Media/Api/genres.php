<?php

/*
|--------------------------------------------------------------------------
| Media API routes - Genres
|--------------------------------------------------------------------------
|
| All API routes for genres
|
*/

Route::namespace('Favon\Media\Http\Controllers\Api')->prefix('api')->group(function () {
    Route::get('/genres', 'GenreController@index')->name('media.api.genres.index');
});
