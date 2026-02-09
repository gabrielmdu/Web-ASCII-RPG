<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private function postRegister(array $data = []): TestResponse
    {
        $pass = Str::password();

        return $this->postJson('/register', array_merge([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => $pass,
            'password_confirmation' => $pass,
        ], $data));
    }

    public function test_user_can_register_with_valid_input()
    {
        $response = $this->postRegister([
            'name' => 'Mark Damon',
            'email' => 'my.awesome.mail_2112@example.com',
            'password' => '2ThisIsMyPassword2',
            'password_confirmation' => '2ThisIsMyPassword2',
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('users', [
            'name' => 'Mark Damon',
            'email' => 'my.awesome.mail_2112@example.com',
            'email_verified_at' => null,
        ]);
    }

    public function test_password_must_be_at_least_8_chars(): void
    {
        $pass = Str::password(7);

        $response = $this->postRegister([
            'password' => $pass,
            'password_confirmation' => $pass,
        ]);

        $response
            ->assertUnprocessable()
            ->assertOnlyJsonValidationErrors('password');
    }

    public function test_password_must_be_at_most_64_chars(): void
    {
        $pass = Str::password(65);

        $response = $this->postRegister([
            'password' => $pass,
            'password_confirmation' => $pass,
        ]);

        $response
            ->assertUnprocessable()
            ->assertOnlyJsonValidationErrors('password');
    }

    public function test_password_must_have_at_least_a_number(): void
    {
        $pass = Str::password(32, numbers: false);

        $response = $this->postRegister([
            'password' => $pass,
            'password_confirmation' => $pass,
        ]);

        $response
            ->assertUnprocessable()
            ->assertOnlyJsonValidationErrors('password');
    }

    public function test_password_must_have_at_least_a_letter(): void
    {
        $pass = Str::password(32, letters: false);

        $response = $this->postRegister([
            'password' => $pass,
            'password_confirmation' => $pass,
        ]);

        $response
            ->assertUnprocessable()
            ->assertOnlyJsonValidationErrors('password');
    }

    public function test_password_must_have_confirmation(): void
    {
        $response = $this->postRegister([
            'password' => 'Abcd1234',
            'password_confirmation' => 'Abcd12345',
        ]);

        $response
            ->assertUnprocessable()
            ->assertOnlyJsonValidationErrors('password');
    }

    public function test_name_is_required(): void
    {
        $response = $this->postRegister([
            'name' => '',
        ]);

        $response
            ->assertUnprocessable()
            ->assertOnlyJsonValidationErrors('name');
    }

    public function test_name_must_have_at_most_24_chars(): void
    {
        $response = $this->postRegister([
            'name' => Str::random(25),
        ]);

        $response
            ->assertUnprocessable()
            ->assertOnlyJsonValidationErrors('name');
    }

    public function test_email_is_required(): void
    {
        $response = $this->postRegister([
            'email' => '',
        ]);

        $response
            ->assertUnprocessable()
            ->assertOnlyJsonValidationErrors('email');
    }

    public function test_email_must_be_valid(): void
    {
        $response = $this->postRegister([
            'email' => 'invalidemail',
        ]);

        $response
            ->assertUnprocessable()
            ->assertOnlyJsonValidationErrors('email');
    }

    public function test_register_must_have_unique_email(): void
    {
        $email = 'my.unique.mail@example.com';

        $response = $this->postRegister([
            'email' => $email,
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);

        // try to create a new user with an already taken e-mail

        Auth::logout();

        $response = $this->postRegister([
            'name' => 'New user',
            'email' => $email,
        ]);

        $response
            ->assertUnprocessable()
            ->assertOnlyJsonValidationErrors('email');
    }
}
