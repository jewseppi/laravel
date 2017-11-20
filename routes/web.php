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

/*
Route::get('/hello', function() {
   return '<h1>Hello World</h1>';
});

Route::get('/users/{id}/{name}', function($id, $name) {
    return 'This is user '.$name.' with an id of '.$id;
});
*/

Auth::routes();

Route::get('/', 'PurchasesController@index')->middleware('auth');
Route::get('/home', 'PurchasesController@index')->middleware('auth');

Route::resource('emails', 'EmailsController')->middleware('auth');
Route::resource('purchases', 'PurchasesController')->middleware('auth');