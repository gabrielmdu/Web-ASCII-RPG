<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueActiveGameSession implements ValidationRule
{
    public function __construct(
        private User $user
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $session = $this->user->gameSessions()
            ->active()
            ->where('game_id', $value)
            ->exists();

        if ($session) {
            $fail('You already have an active session of that game.');
        }
    }
}
