<?php

use Illuminate\Http\Request;

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

Route::get('waypoints', 'TMAPIController@pointinfo')->name('api.waypoints');
Route::get('travelers/{traveler}/pointinfo', 'TMTravelerController@clinchedRoutes')->name('traveler.routes');