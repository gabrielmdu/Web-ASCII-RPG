<?php

namespace App;

use App\Exceptions\WarpgException;
use App\SceneOption;
use Illuminate\Http\Response;
use Jenssegers\Mongodb\Eloquent\Model;
use Throwable;

class Scene extends Model
{
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function getOption(int $option): SceneOption
    {
        try {
            return new SceneOption($this->options[$option]);
        } catch (Throwable $t) {
            throw new WarpgException('Invalid option for scene '. $t->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
