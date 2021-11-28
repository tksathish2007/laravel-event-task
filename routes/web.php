<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'MainController@index');
Route::post('event/add', 'MainController@add');
Route::post('event/update', 'MainController@update');
Route::get('event/delete', 'MainController@delete');
Route::get('event/export', 'MainController@export');