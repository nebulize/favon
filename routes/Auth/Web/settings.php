<?php

/*
|--------------------------------------------------------------------------
| Settings routes
|--------------------------------------------------------------------------
|
| All routes for user settings.
|
*/

Route::namespace('Favon\Auth\Http\Controllers')->group(function() {
    Route::post('/me/notifications', 'AuthController@notifications')->name('users.settings.notifications');
});
