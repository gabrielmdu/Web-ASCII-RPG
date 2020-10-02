<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

/**
 * Class that tests methods related to user and authentication
 */
class AuthTest extends TestCase
{
    const USER_TEST_EMAIL = 'test@warpg.com';
    const USER_TEST_PASS = '123';

    /**
     * Tests if a new user can be created and inserted into the database.
     *
     * @return User The created user
     */
    public function testCanCreateUser()
    {
        $user = factory(User::class)->make([
            'email' => self::USER_TEST_EMAIL,
            'password' => password_hash(self::USER_TEST_PASS, PASSWORD_DEFAULT),
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue($user->save());

        return $user;
    }

    /**
     * Tests if the user can login with the sample test user credentials
     *
     * @depends testCanCreateUser
     * @return string Newly created user token
     */
    public function testCanLogin()
    {
        $token = $this->postJSON(
            '/v1/login',
            [
                'email' => self::USER_TEST_EMAIL,
                'password' => self::USER_TEST_PASS,
            ])
            ->assertOk()
            ->assertJsonStructure(['access_token'])
            ->decodeResponseJson('access_token');

        $this->assertInstanceOf(User::class, auth()->user());

        return $token;
    }

    /**
     * Tests if login can be refreshed/renewed
     *
     * @depends testCanLogin
     * @param string $originalToken
     * @return string
     */
    public function testCanRefreshLogin(string $originalToken)
    {
        $newToken = $this->postJson('/v1/refresh', [], [
            'Authorization' => 'Bearer ' . $originalToken
        ])
            ->assertOk()
            ->assertJsonStructure(['access_token'])
            ->decodeResponseJson('access_token');

        $this->assertNotEquals($originalToken, $newToken);

        return $originalToken;
    }
    
    /**
     * testCantUseBlacklistedToken
     *
     * @depends testCanRefreshLogin
     * @param  mixed $token
     * @return void
     */
    public function testCantUseBlacklistedToken(string $token)
    {
        $this->get('/v1/game', [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])
            ->assertUnauthorized();
    }

    /**
     * Tests if the user can be removed
     *
     * @depends testCanRefreshLogin
     * @param string $token
     * @return void
     */
    public function testCanRemoveUser(string $token)
    {
        $user = User::first();

        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue($user->delete());
    }
}
