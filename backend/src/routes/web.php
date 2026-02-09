<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

// replace the default Fortify verification endpoint
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
    ->middleware(['signed', 'throttle:' . config('fortify.limiters.verification', '6,1')])
    ->name('verification.verify');
