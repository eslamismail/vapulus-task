<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Broadcast::routes(['middleware' => ['auth:api']]);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('users', 'UserController@index');
    Route::get('users/all', 'UserController@all');
    Route::get('rooms', 'RoomController@index');
    Route::get('rooms/{id}', 'RoomController@show');
    Route::post('/rooms/{id}/message', 'RoomController@sendMessage');
    Route::post('/rooms/create', 'RoomController@store');
    Route::post('logout', 'AuthController@logout');
    Route::post('/rooms/createGroup', 'RoomController@storeGroup');
});
