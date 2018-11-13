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

Route::resource('catalog', 'catalogController');

Route::resource('orderHistory', 'orderHistoryViewController');

Route::get('getOrderToken', 'catalogController@getCurrentToken');

Route::put('/changeStatus/{id}', 'catalogController@changeStatus')->name('changeStatus');

Route::put('/changeDiscount/{id}', 'catalogController@changeDiscount')->name('changeDiscount');

Route::put('/updateQuantity/{id}', 'catalogController@updateQuantity')->name('updateQuantity');

Route::post('/newInventory', 'catalogController@newInventory')->name('newInventory');

Route::get('/InvRemarks', 'catalogController@InvRemarks')->name('InvRemarks');