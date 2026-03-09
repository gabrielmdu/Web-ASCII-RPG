<?php

namespace App\Http\Requests\Concerns;

trait NormalizeInputTrait
{
    protected function toBoolean($value, bool $default = true): ?bool
    {
        if ($value === null) {
            return $default;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }
}
