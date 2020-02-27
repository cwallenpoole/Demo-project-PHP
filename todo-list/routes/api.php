<?php

use \Illuminate\Support\Facades\Route;
use \Illuminate\Http\Request;

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
    Route::get('/user', function (Request $request) {
        return json_encode(['data' => $request->user()], 128);
    });
});
