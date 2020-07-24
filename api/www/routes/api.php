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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::group(['middleware' => ['sessions']], function () {
Route::get('/game', 'GameController@getCurrentGame')->middleware(['auth:api', 'user.only']);
Route::get('/game/reset', 'GameController@resetCurrentGame')->middleware(['auth:api', 'user.only']);
Route::get('/scene', 'SceneController@getCurrentScene')->middleware(['auth:api', 'user.only']);
Route::post('/scene', 'SceneController@setCurrentScene')->middleware(['auth:api', 'user.only']);
//});

Route::post('login', 'AuthController@login');
Route::post('logout', 'AuthController@logout');
Route::post('refresh', 'AuthController@refresh');
