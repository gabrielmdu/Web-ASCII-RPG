<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * Tests if the user can login with the sample admin user credentials.
     *
     * @return void
     */
    public function testCanLogin()
    {
        return $this->postJSON(
            '/v1/login',
            [
                'email' => 'admin@admin.com',
                'password' => '123',
            ])
            ->assertOk()
            ->assertJsonStructure(['access_token'])
            ->decodeResponseJson('access_token');
    }
}
