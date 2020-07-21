<?php

namespace App;

use App\Exceptions\WarpgException;
use Jenssegers\Mongodb\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;

class GameStatus extends Model
{
    public function setCurrentScene(string $scene)
    {
        $options = Game::find($this->game_id)
            ->getScene($this->scene)
            ->options;

        if (!in_array($scene, array_column($options, 'destiny'))) {
            throw new WarpgException('Invalid scene', Response::HTTP_BAD_REQUEST);
        }

        $this->scene = $scene;
        return $this->save();
    }

    public function resetCurrentGame()
    {
        $startingScene = Game::find($this->game_id)->starting_scene;

        $this->scene = $startingScene;
        return $this->save();
    }
}
