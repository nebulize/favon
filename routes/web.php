<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/tv/seasonal', 'BaseController@seasonal')->name('tv.seasonal');
Route::get('/seasonal/{year}/{season}', 'BaseController@index')->name('tv.seasonal.index');
Route::any('adminer', '\Miroc\LaravelAdminer\AdminerController@index');

Auth::routes();
Route::get('/me/confirm-email/{token}', 'Auth\AuthController@confirmEmail')->name('me.confirm-email');
Route::post('/me/notifications', 'Auth\AuthController@notifications')->name('me.notifications');

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth', 'prefix' => '/api'], function() {
    Route::get('/users/me', function (\Illuminate\Http\Request $request) {
        return $request->user();
    });
    Route::post('/users/me/seasons', 'ApiController@addTvSeasonToList')->name('api.users.list.seasons.add');
    Route::patch('/users/me/seasons/{id}', 'ApiController@updateTvSeasonListStatus')->name('api.users.list.seasons.update');
    Route::delete('/users/me/seasons/{id}', 'ApiController@removeTvSeasonFromList')->name('api.users.list.seasons.delete');
});

Route::get('/api/seasonal/{seasonId}', 'BaseController@indexApi')->name('api.tv.seasonal.index');
