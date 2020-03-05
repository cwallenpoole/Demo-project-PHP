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

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/list/new', 'TodoListController@create')
    ->name('list.new');

Route::get('/list/{todoList}/edit', 'TodoListController@edit')
    ->where(['todoList' => '^[0-9]+$'])
    ->name('list.edit');

Route::get('/list/{id}', 'TodoListController@show')->name('list.view');
Route::post('/list/update', 'TodoListController@update')->name('list.update');


Route::get('/list/{todoList}/entry/new', 'TodoEntryController@create')
    ->name('entry.new');

Route::get('/list/{list_id}/entry/{entryId}/edit', 'TodoEntryController@edit')
    ->where(['todoList' => '^[0-9]+$'])
    ->where(['list_id' => '^[0-9]+$'])
    ->name('list.edit');

Route::get('/list/{todoList}/entry/{entryId}', 'TodoEntryController@show')->name('list.view');

Route::post('/list/entry/update', 'TodoEntryController@update')->name('list.update');
Route::get('/list/update', 'TodoListController@update')->name('list.update');