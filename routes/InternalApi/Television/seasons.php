<?php

/*
|--------------------------------------------------------------------------
| Television API routes - Seasons
|--------------------------------------------------------------------------
|
| All API routes for tv seasons.
|
*/

Route::namespace('Favon\Television\Http\Controllers\Api')->prefix('api')->group(function () {
    Route::get('/seasonal/{id}', 'TvSeasonController@index');

    Route::middleware(['auth'])->group(function () {
        Route::post('/users/me/seasons', 'ListController@store');
        Route::patch('/users/me/seasons/{id}', 'ListController@update');
        Route::delete('/users/me/seasons/{id}', 'ListController@delete');
    });
});
