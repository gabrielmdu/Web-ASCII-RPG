<?php

namespace App\Rules;

use App\Models\GameSession;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateChoiceIndex implements ValidationRule
{
    public function __construct(
        private GameSession $session
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $this->session->loadCount('currentChoices');

        if ($this->session->current_choices_count < $value) {
            $fail('Invalid choice index.');
        }
    }
}
