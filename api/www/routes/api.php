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

Route::get('/game', 'GameController@getCurrentGame')->middleware(['auth:api', 'user.only']);
Route::post('/game', 'GameController@setCurrentGame')->middleware(['auth:api', 'user.only']);
Route::get('/game/reset', 'GameController@resetCurrentGame')->middleware(['auth:api', 'user.only']);
Route::get('/game/list', 'GameController@getGameList');

Route::get('/scene', 'SceneController@getCurrentScene')->middleware(['auth:api', 'user.only']);
Route::post('/scene', 'SceneController@chooseSceneOption')->middleware(['auth:api', 'user.only']);

Route::post('login', 'AuthController@login');
Route::post('logout', 'AuthController@logout');
Route::post('refresh', 'AuthController@refresh');
