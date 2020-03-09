<?php

use \Illuminate\Support\Facades\Route;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('/admin/email-exists', 'Auth\AdministrationController@doesEmailExist');
    Route::get('/admin/is-token-valid', 'Auth\AdministrationController@isTokenValid');


    Route::post('/list/update', 'TodoListController@update')->name('list.update');
    Route::post('/list/entry/update', 'TodoEntryController@update')->name('entry.update');

    Route::get('/list/{todoList}', 'TodoListController@show')->name('list.view');

    Route::get('/list/{todoList}/entry/{entry}', 'TodoEntryController@show')->name('list.view');

    Route::delete('/list/{todoList}', 'TodoListController@destroy')->name('list.delete');
    Route::delete('/list/{todoList}/entry/{entry}', 'TodoEntryController@destroy')->name('entry.delete');

    Route::get('/user', function (Request $request) {
        $user = Auth::user();
        return json_encode(['data' => $user->toArray() + ['lists' => $user->lists]], 128);
    });
});
