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

Auth::routes(['register' => false]);
Route::get('auth/token','Auth\LoginController@getToken');
Route::post('auth/token','Auth\LoginController@postToken');


Route::middleware('auth')->group(function (){

    //SPA
    Route::get('/{any}', function (){
        return view('dashboard');
    })->where('any','.*');


});

