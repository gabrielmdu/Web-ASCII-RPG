<?php

namespace Tests;

trait HttpHeaderTrait
{
    /**
     * Returns the Authorization HTTP header for
     * bearer tokens
     *
     * @param string $token
     * @return array
     */
    public function getAuthHeader(string $token): array
    {
        return ['Authorization' => 'Bearer ' . $token];
    }
}