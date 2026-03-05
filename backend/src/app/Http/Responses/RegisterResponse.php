<?php

namespace App\Http\Responses;

use App\Http\Resources\UserResource;
use Laravel\Fortify\Contracts\RegisterResponse as ContractsRegisterResponse;

class RegisterResponse implements ContractsRegisterResponse
{
    public function toResponse($request)
    {
        $user = $request->user()
            ->loadMissing('activeGameSessions.game');

        return new UserResource($user);
    }
}
