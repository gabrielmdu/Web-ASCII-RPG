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
            'password' => password_hash(self::USER_TEST_PASS, PASSWORD_DEFAULT)
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
        $token = $this->createNewLoginToken();

        $this->assertInstanceOf(User::class, auth()->user());

        return $token;
    }

    /**
     * Tests if login can be refreshed/renewed, then sends the original
     * token to the next test which should fail to authorize
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
     * Tests if the user can be logged out
     *
     * @return void
     */
    public function testCanLogoutUser()
    {
        $token = $this->createNewLoginToken();

        $this->postJSON(
            '/v1/logout', [
                'Authorization' => 'Bearer ' . $token
            ])
            ->assertOk()
            ->assertJsonFragment(['success' => true]);

        return $token;
    }

    /**
     * Tests if a blacklisted token should get an unauthorized status
     *
     * @depends testCanRefreshLogin
     * @depends testCanLogoutUser
     * @param string $token
     * @return void
     */
    public function testCantUseBlacklistedToken(string $tokenRefresh, string $tokenLogout)
    {
        foreach ([$tokenRefresh, $tokenLogout] as $token) {
            $this->get('/v1/game', [
                'Authorization' => 'Bearer ' . $token
            ])
                ->assertUnauthorized();
        }
    }
    
    /**
     * Creates and asserts a new login token
     *
     * @return void
     */
    private function createNewLoginToken()
    {
        return $this->postJSON(
            '/v1/login',
            [
                'email' => self::USER_TEST_EMAIL,
                'password' => self::USER_TEST_PASS
            ])
            ->assertOk()
            ->assertJsonStructure(['access_token'])
            ->decodeResponseJson('access_token');
    } 
}
