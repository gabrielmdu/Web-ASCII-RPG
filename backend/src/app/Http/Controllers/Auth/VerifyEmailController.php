<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Overwrites the default Fortify verification method to allow 
     * guest users to verify their email from the verification link
     * without having to log in first.
     */
    public function verify(Request $request, $id, $hash)
    {
        // find the user by ID
        $user = User::findOrFail($id);

        // validate the hash (security check)
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Invalid verification link.'], 403);
        }

        // check if already verified
        if ($user->hasVerifiedEmail()) {
            // log them in anyway so they land in the app
            Auth::login($user);
            return response()->json([
                'message' => 'Email already verified.',
                'user' => $user->toResource(),
            ]);
        }

        // mark as verified
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // log the user in
        Auth::login($user);

        return response()->json([
            'message' => 'Email verified successfully.',
            'user' => $user->toResource(),
        ]);
    }
}
