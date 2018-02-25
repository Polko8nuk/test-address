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


Route::get('/', 'HomeController@index');

Route::get('/task', 'HomeController@task');

Route::get('/delete/{id}', 'HomeController@delete')->name('DeleteAddress');

Route::post('/addAddress', 'HomeController@addAddress');

Route::post('/getcities', 'HomeController@getCitiesPost');


