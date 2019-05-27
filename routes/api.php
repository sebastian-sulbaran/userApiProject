<?php

use Illuminate\Http\Request;

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


/*Route::middleware('auth:api')->group(function (){
});*/
Route::get('users/','UserController@index');
Route::post('users/','UserController@store');
Route::put('users/{id}','UserController@update');
Route::get('users/{id}','UserController@show');
Route::delete('users/{id}','UserController@delete');
