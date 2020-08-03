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

    public function storeItem(int $option, string $itemId)
    {
        $optItem = $this
            ->game
            ->getScene($this->scene)
            ->options[$option]['item'];

        // checks if the item belongs to the scene
        if ($optItem !== $itemId) {
            throw new WarpgException("Item '{$itemId}' doesn't belong to the scene", Response::HTTP_BAD_REQUEST);
        }

        $item = array_filter($this->game->items, fn ($i) => $i['id'] === $itemId)[0];

        if ($item['unique'] && in_array($itemId, $this->items)) {
            throw new WarpgException('Item already in inventory', Response::HTTP_BAD_REQUEST);
        }

        $result = DB::collection('game_statuses')
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

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
