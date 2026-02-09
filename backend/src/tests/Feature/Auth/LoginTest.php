<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private function createAndLoginUser(): User
    {
        $pass = 'password123';

        $user = User::factory()->create([
            'password' => Hash::make($pass),
        ]);

        $this->postJson('/login', [
            'email' => $user->email,
            'password' => $pass,
        ])->assertOk();

        return $user;
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = $this->createAndLoginUser();

        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $pass = 'password123';

        $user = User::factory()->create([
            'password' => Hash::make($pass),
        ]);

        $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'wrong_pass123',
        ])->assertUnprocessable();

        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = $this->createAndLoginUser();

        $this->assertAuthenticatedAs($user);

        $this->postJson('/logout');

        $this->assertGuest();
    }
}
