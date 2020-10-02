<?php

namespace App\Http\Controllers;

use App\Exceptions\WarpgException;
use App\Http\Controllers\Controller;
use App\Http\Utilities\JsonUtility;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    private const TTL_WEEK = 60 * 24 * 7;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        if (auth()->check()) {
            throw new WarpgException('User already authenticated', Response::HTTP_BAD_REQUEST);
        }

        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            throw new WarpgException('Invalid credentials', Response::HTTP_UNAUTHORIZED);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return JsonUtility::respond(true, 'Successfully logged out');
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'expires_in' => auth()->factory()->getTTL() * self::TTL_WEEK
        ];
    }
}
