<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function me(Request $request)
    {
        $user = $request->user();
        $user->load('activeGameSessions');

        return new UserResource($user);
    }
}
