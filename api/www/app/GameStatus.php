<?php

namespace App;

use App\Exceptions\WarpgException;
use Jenssegers\Mongodb\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class GameStatus extends Model
{
    public function getCurrentScene()
    {
        return $this->game->getScene($this->scene);
    }

    public function setCurrentScene(string $scene)
    {
        $this->scene = $scene;
        return $this->save();
    }

    public function setCurrentGame(string $gameId)
    {
        $this->game_id = $gameId;
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

        if ($item['unique'] && $this->getItem($itemId)) {
            throw new WarpgException('Item already in inventory', Response::HTTP_BAD_REQUEST);
        }

        $result = $this->push('items', [
            'id' => $itemId,
            'used' => false
        ]);

        if (!$result) {
            throw new WarpgException("Error while storing item '{$itemId}'",
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $item;
    }

    public function useItem(array $item): bool
    {
        if (!$item['used']) {
            // removes the item from the items array
            $this->pull('items', $item);

            // defines item as used and add it to the array
            $item['used'] = true;
            $this->push('items', $item);

            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Gets the item from the current game status
     *
     * @param  string $id The item's id
     * @return array|null The item or null if not found
     */
    public function getItem(string $id)
    {
        try {
            return array_filter($this->items, fn ($item) => $item['id'] === $id)[0];
        } catch (Throwable $t) {
            return null;
        }
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
