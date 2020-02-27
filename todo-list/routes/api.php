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
Route::get('email-exists', 'Auth\AdministrationController@doesEmailExist');

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('/user', function (Request $request) {
        return json_encode($request->user());
    });
});
