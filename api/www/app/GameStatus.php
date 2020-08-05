<?php

namespace App;

use App\Exceptions\WarpgException;
use Illuminate\Support\Facades\DB;
use Jenssegers\Mongodb\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;

class GameStatus extends Model
{
    public function setCurrentScene(string $scene)
    {
        $this->scene = $scene;
        return $this->save();
    }

    public function resetCurrentGame()
    {
        $startingScene = $this->game->starting_scene;

        $this->scene = $startingScene;
        $this->items = [];
        return $this->save();
    }

    public function storeItem(int $option)
    {
        $itemId = $this
            ->game
            ->getScene($this->scene)
            ->options[$option]['item']['id'];

        $item = $this->game->getItem($itemId);

        if ($item['unique'] && $this->hasItem($itemId)) {
            throw new WarpgException('Item already in inventory', Response::HTTP_BAD_REQUEST);
        }

        $result = DB::collection($this->getTable())
            ->where('_id', $this->id)
            ->update([
                '$push' => [
                    'items' => $itemId
                ]
            ]);

        if (!$result) {
            throw new WarpgException("Error while storing item '{$itemId}'", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $item;
    }

    public function hasItem(string $itemId): bool
    {
        return in_array($itemId, $this->items);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
