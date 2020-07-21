<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Game extends Model
{
    public function scenes()
    {
        return $this->hasMany(Scene::class);
    }

    public function getScene(string $sceneId)
    {
        return $this
            ->scenes()
            ->where('id', $sceneId)
            ->first();
    }
}
