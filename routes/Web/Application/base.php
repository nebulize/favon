<?php

/*
|--------------------------------------------------------------------------
| Application Web routes - Base
|--------------------------------------------------------------------------
|
| All base routes for the application.
|
*/

Route::get('/', function () {
    return redirect()->route('television.seasonal.show');
});
Route::any('adminer', '\Miroc\LaravelAdminer\AdminerController@index');
