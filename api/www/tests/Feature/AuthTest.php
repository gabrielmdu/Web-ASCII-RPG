<?php

namespace Tests\Feature;

use App\User;
use Tests\HttpHeaderTrait;
use Tests\TestCase;

/**
 * Class that tests methods related to user and authentication
 */
class AuthTest extends TestCase
{
    use HttpHeaderTrait;

    const USER_TEST_EMAIL = 'test@warpg.com';
    const USER_TEST_PASS = '123';

    /**
     * Tests if a new user can be created and inserted into the database.
     *
     * @return User The created user
     */
    public function testCanCreateUser(): User
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
    public function testCanLogin(): string
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
    public function testCanRefreshLogin(string $originalToken): string
    {
        $newToken = $this->postJson(
            '/v1/refresh',
            [],
            $this->getAuthHeader($originalToken))
            ->assertOk()
            ->assertJsonStructure(['access_token'])
            ->decodeResponseJson('access_token');

        $this->assertNotEquals($originalToken, $newToken);

        return $originalToken;
    }

    /**
     * Tests if the user can be logged out
     *
     * @return string
     */
    public function testCanLogoutUser(): string
    {
        $token = $this->createNewLoginToken();

        $this->postJSON(
            '/v1/logout', $this->getAuthHeader($token))
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
            $this->get('/v1/game', $this->getAuthHeader($token))
                ->assertUnauthorized();
        }
    }

    /**
     * Creates and asserts a new login token
     *
     * @return string
     */
    private function createNewLoginToken(): string
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
