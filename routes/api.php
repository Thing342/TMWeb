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

/*
\Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
    echo'<pre>';
    var_dump($query->sql);
    var_dump($query->bindings);
    var_dump($query->time);
    echo'</pre>';
});
*/

Route::get('routes/{route}/waypoints', 'TMRouteController@waypoints')->name('route.waypoints');
Route::get('routes/{route}/segments', 'TMRouteController@segments')->name('route.segments');
Route::get('connRoutes/{connRte}', 'TMRouteController@connectedRoute')->name('connRte.read');

Route::get('travelers/{traveler}/routes/{root}', 'TMTravelerController@clinchedSegments')->name('traveler.segments');
Route::get('travelers/{traveler}/routes', 'TMTravelerController@clinchedRoutes')->name('traveler.routes');