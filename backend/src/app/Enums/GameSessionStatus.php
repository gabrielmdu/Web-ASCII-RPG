<?php

namespace App\Enums;

enum GameSessionStatus: string
{
    case ACTIVE = "active";
    case FINISHED = "finished";
    case ABANDONED = "abandoned";
}
