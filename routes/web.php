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

Route::get('/', function () {
    return view('home');
})->name('home');


Route::get('/routes', 'TMRouteController@browser')->name('route.browser');
Route::get('/routes/bySystem', 'TMSystemController@index')->name('system.index');
Route::get('/routes/byRegion', 'TMRegionController@index')->name('region.index');
//Route::get('/routes/byConnRte', 'TMConnRouteController@index')->name('region.index');

Route::get('/routes/bySystem/{system}', 'TMRouteController@indexBySystem')->name('route.index.system');
Route::get('/routes/byRegion/{region}', 'TMRouteController@indexByRegion')->name('route.index.region');
Route::get('/routes/byConnRte/{connRte}', 'TMRouteController@indexByConnRte')->name('route.index.conn');
Route::get('/routes/bySystemRegion/{system}/{region}', 'TMRouteController@index')->name('route.index');

Route::get('/routes/{route}', 'TMRouteController@read')->name('route.read');

Route::get('/users/{traveler}/mapview', 'TMTravelerController@mapview')->name('mapview');
Route::post('/users/change', 'TMTravelerController@changeUser')->name('travelers.change');
