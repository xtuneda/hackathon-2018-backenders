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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/join/{userId}', 'HomeController@join')->name('join');
Route::get('/leave/{userId}', 'HomeController@leave')->name('leave');
Route::get('/activate/{userId}', 'HomeController@activate')->name('activate');
Route::get('/remove/{userId}', 'HomeController@remove')->name('remove');
Route::get('/done', 'HomeController@done')->name('done');
