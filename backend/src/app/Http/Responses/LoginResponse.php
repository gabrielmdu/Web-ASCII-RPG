<?php

namespace App\Http\Responses;

use App\Http\Resources\UserResource;
use Laravel\Fortify\Contracts\LoginResponse as ContractsLoginResponse;

class LoginResponse implements ContractsLoginResponse
{
    public function toResponse($request)
    {
        $user = $request->user()
            ->loadMissing('activeGameSessions.game');

        return new UserResource($user);
    }
}
