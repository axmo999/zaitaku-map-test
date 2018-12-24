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
    return view('welcome');
});

Route::resource('/facility', 'FacilityController');

Route::get('/index3', function () {
    return view('html.index3');
});


Route::get('/view', function () {
    return view('html.index');
});
