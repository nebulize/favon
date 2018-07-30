<?php

/*
|--------------------------------------------------------------------------
| Auth API routes - Users
|--------------------------------------------------------------------------
|
| All API routes for users.
|
*/

Route::namespace('Favon\Auth\Http\Controllers\Api')->middleware(['auth'])->prefix('api')->group(function() {
    Route::get('/users/me', function (\Illuminate\Http\Request $request) {
        return $request->user();
    });
});
