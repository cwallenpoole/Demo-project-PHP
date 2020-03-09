<?php

use \Illuminate\Support\Facades\Route;
use \Illuminate\Support\Facades\Auth;
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

Route::group(['middleware' => 'auth'], function(){
    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/list/new', 'TodoListController@create')
        ->name('list.new');

    Route::get('/list/{todoList}/edit', 'TodoListController@edit')
        ->where(['todoList' => '^[0-9]+$'])
        ->name('list.edit');


    Route::get('/list/{todoList}/entry/{todoEntry}/edit', 'TodoEntryController@edit')
        ->where(['todoList' => '^[0-9]+$'])
        ->where(['todoEntry' => '^[0-9]+$'])
        ->name('entry.edit');

    Route::get('/list/{todoList}/entry/new', 'TodoEntryController@create')
        ->name('entry.new');

    Route::post('/list/update', 'TodoListController@update')->name('list.update');
    Route::post('/list/entry/update', 'TodoEntryController@update')->name('entry.update');


    // We're just using POST here because this is a browser action not controlled by AJAX.
    Route::post('/list/{todoList}/delete', 'TodoListController@destroy')
        ->where(['todoList' => '^[0-9]+$'])
        ->name('list.delete');

    Route::post('/list/{todoList}/entry/{todoEntry}/delete', 'TodoEntryController@destroy')
        ->where(['todoList' => '^[0-9]+$'])
        ->where(['todoEntry' => '^[0-9]+$'])
        ->name('entry.delete');
});