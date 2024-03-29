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

Route::group(['middleware' => ['web', 'guest']], function(){
    Route::get('connect', 'AuthController@connect')->name('connect');
});

Route::get('login_form', function(){
    return view('login');
});

Route::get('home', function(){
    return view('home');
});