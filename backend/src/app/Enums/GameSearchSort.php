<?php

namespace App\Enums;

enum GameSearchSort: string
{
    case ID = 'id';
    case NAME = 'name';
    case CREATED_AT = 'created_at';
    case LAST_MODIFIED = 'last_modified';
    case CREATOR_NAME = 'creator_name';
}
