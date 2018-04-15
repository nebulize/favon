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

Route::get('/home', 'HomeController@index')->name('home');
