<?php

use Illuminate\Http\Request;
use App\Http\Controllers\UrlShortenerController;

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


Route::group(['prefix' => 'v1'], function () {
    Route::post('UrlShortener/shorten', [UrlShortenerController::class, 'shorten']);
    Route::get('UrlShortener/{shortened}', [UrlShortenerController::class, 'redirect']);
});

