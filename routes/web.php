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

Route::get('/', function () {
    return view('index');
});

Route::post('/product_add','App\Http\Controllers\ProductController@add');
Route::get('/get_data','App\Http\Controllers\ProductController@get_data');
Route::post('/deleteProduct','App\Http\Controllers\ProductController@deletep');
Route::post('/editProduct','App\Http\Controllers\ProductController@editp');
Route::post('/product_update','App\Http\Controllers\ProductController@update');


