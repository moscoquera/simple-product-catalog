<?php


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


Route::middleware('auth:api')->group(function () {
    Route::resource('categories','CategoryController')->only([ 'store', 'update', 'destroy']);
    Route::resource('products','ProductController')->only([ 'store', 'update', 'destroy']);
});

Route::middleware(['cors'])->group(function (){
    Route::resource('categories','CategoryController')->only([ 'index', 'show']);
    Route::resource('products','ProductController')->only([ 'index', 'show']);
    Route::get('/categories/{category}/products','CategoryController@products');
});


