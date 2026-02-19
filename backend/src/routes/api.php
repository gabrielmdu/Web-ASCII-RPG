<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\GameSceneController;
use App\Http\Controllers\UserGameSessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return response()->json(['user' => $request->user()->toResource()]);
})->name('user')
    ->middleware('auth:sanctum');

Route::name('user.game-sessions.')
    ->prefix('users/{user}/game-sessions')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/', [UserGameSessionController::class, 'index'])->name('index');
        Route::post('/', [UserGameSessionController::class, 'store'])->name('store');
        Route::get('/{session}', [UserGameSessionController::class, 'show'])->name('show');
        //Route::match(['put', 'patch'], '/{post}', [PostController::class, 'update'])->name('update');
        //Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');
    });

Route::name('game.')
    ->prefix('games')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/', [GameController::class, 'index'])->name('index');
        //Route::post('/', [PostController::class, 'store'])->name('store');
        Route::get('/{game}', [GameController::class, 'show'])->name('show');
        //Route::match(['put', 'patch'], '/{post}', [PostController::class, 'update'])->name('update');
        //Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');
    });

Route::name('game.scenes.')
    ->prefix('games/{game}/scenes')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/', [GameSceneController::class, 'index'])->name('index');
        //Route::post('/', [PostController::class, 'store'])->name('store');
        Route::get('/{scene}', [GameSceneController::class, 'show'])->name('show');
        //Route::match(['put', 'patch'], '/{post}', [PostController::class, 'update'])->name('update');
        //Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');
    });
