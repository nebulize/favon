<?php

/*
|--------------------------------------------------------------------------
| Television web routes - Seasonal
|--------------------------------------------------------------------------
|
| All routes for the seasonal overview.
|
*/

Route::namespace('Favon\Television\Http\Controllers')->prefix('tv')->group(function() {
  Route::get('/seasonal/{year?}/{season?}', 'SeasonalController@show')->name('television.seasonal.show');
});
