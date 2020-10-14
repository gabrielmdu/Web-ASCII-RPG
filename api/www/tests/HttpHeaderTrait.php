<?php

namespace Tests;

use App\User;

trait HttpHeaderTrait
{
    /**
     * Returns the Authorization HTTP header for
     * bearer tokens
     *
     * @param string $token
     * @return array
     */
    public function getAuthHeader(string $token = ''): array
    {
        if (empty($token)) {
            $user = User::first();
            $token = auth()->login($user);
        }

        return ['Authorization' => 'Bearer ' . $token];
    }
}