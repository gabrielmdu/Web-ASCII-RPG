<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    private function createUnverifiedUser(): User
    {
        return User::factory()
            ->unverified()
            ->create();
    }

    public function test_unverified_user_is_sent_verification_notification(): void
    {
        Notification::fake();

        $user = $this->createUnverifiedUser();

        $this->actingAs($user)
            ->postJson('/email/verification-notification')
            ->assertAccepted();

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_verified_user_cannot_be_sent_verification_notification(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/email/verification-notification')
            ->assertNoContent();

        Notification::assertNothingSent();
    }

    public function test_cannot_send_notification_to_guest(): void
    {
        $request = $this->postJson('/email/verification-notification');

        $request->assertUnauthorized();
    }

    public function test_unverified_user_can_get_verified(): void
    {
        Notification::fake();

        $user = $this->createUnverifiedUser();

        $this->actingAs($user)
            ->postJson('/email/verification-notification')
            ->assertAccepted(202);

        // assert notification was sent & extract the URL
        Notification::assertSentTo(
            $user,
            VerifyEmail::class,
            function ($notification) use ($user) {

                // This is the signed backend verification URL
                $verificationUrl = URL::temporarySignedRoute(
                    'verification.verify',
                    config('auth.verification.expire', 60),
                    [
                        'id' => $user->getKey(),
                        'hash' => sha1($user->getEmailForVerification()),
                    ]
                );

                // call the verification URL
                $this->actingAs($user)
                    ->get($verificationUrl)
                    ->assertRedirect(); // fortify redirects after success

                return true;
            }
        );

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    public function test_expired_verification_link_does_not_verify_user()
    {
        $user = $this->createUnverifiedUser();

        // create an expired signed URL
        $expiredUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->subMinutes(1),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        $this->actingAs($user)
            ->get($expiredUrl)
            ->assertStatus(403);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }
}
