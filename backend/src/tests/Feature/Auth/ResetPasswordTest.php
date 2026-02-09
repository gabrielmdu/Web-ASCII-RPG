<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_is_sent_to_existing_user()
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'new_user@mail.com',
        ]);

        $this->postJson('/forgot-password', [
            'email' => 'new_user@mail.com',
        ])->assertOk();

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_reset_password_fails_for_non_existent_email()
    {
        $this->postJson('/forgot-password', [
            'email' => 'this_email_inexists@example.com',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');
    }

    public function test_user_can_reset_password_with_valid_token()
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'new_user@mail.com',
            'password' => Hash::make('oldpassword123'),
        ]);

        // request reset link
        $this->postJson('/forgot-password', [
            'email' => 'new_user@mail.com',
        ]);

        // capture token from notification
        Notification::assertSentTo(
            $user,
            ResetPassword::class,
            function ($notification) use ($user) {
                $token = $notification->token;

                // submit a new password
                $this->postJson('/reset-password', [
                    'email' => $user->email,
                    'token' => $token,
                    'password' => 'newpassword123',
                    'password_confirmation' => 'newpassword123',
                ])->assertOk();

                return true;
            }
        );

        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    public function test_reset_password_fails_with_invalid_token()
    {
        $user = User::factory()->create();

        $this->postJson('/reset-password', [
            'email' => $user->email,
            'token' => 'invalid-token',
            'password' => 'password1',
            'password_confirmation' => 'password1',
        ])->assertUnprocessable();
    }
}
