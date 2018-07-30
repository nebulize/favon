<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

include_route_files(__DIR__.'/Auth/Api/');
include_route_files(__DIR__.'/Media/Api/');
include_route_files(__DIR__.'/Television/Api/');
