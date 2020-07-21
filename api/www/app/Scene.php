<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Scene extends Model
{
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
