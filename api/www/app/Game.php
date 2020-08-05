<?php

namespace App;

use App\Exceptions\WarpgException;
use Illuminate\Http\Response;
use Jenssegers\Mongodb\Eloquent\Model;
use Throwable;

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

    public function getItem(string $id): array
    {
        try {
            return array_filter($this->items, fn ($i) => $i['id'] === $id)[0];
        } catch (Throwable $t) {
            throw new WarpgException("Item '{$id}' doesn't belong to the game", Response::HTTP_BAD_REQUEST);
        }
    }
}
