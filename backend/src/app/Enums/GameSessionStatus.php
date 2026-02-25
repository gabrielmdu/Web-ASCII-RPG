<?php

namespace App\Enums;

enum GameSessionStatus: string
{
    case ACTIVE = "active";
    case FINISHED = "finished";
    case ABANDONED = "abandoned";

    public static function order(): array
    {
        return [
            static::ACTIVE->value,
            static::FINISHED->value,
            static::ABANDONED->value,
        ];
    }
}
