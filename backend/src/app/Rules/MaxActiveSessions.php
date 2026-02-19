<?php

namespace App\Rules;

use App\Enums\GameSessionStatus;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MaxActiveSessions implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /** @var User $user */
        $user = Auth::user();
        $user->loadCount(['gameSessions as active_count' => function (Builder $query) {
            $query->where('status', GameSessionStatus::ACTIVE);
        }]);

        if ($user->active_count >= 5) {
            $fail('You reached the limit of 5 active adventures. Finish or abandon one first.');
        }
    }
}
