<?php

namespace App\Http\Controllers;

use App\Game;
use App\Http\Resources\Game as GameResource;
use App\Http\Resources\GameBasicInfo;

class GameController extends Controller
{
    public function getCurrentGame()
    {
        return new GameResource(auth()->user()->getCurrentGame());
    }

    public function resetCurrentGame()
    {
        auth()
            ->user()
            ->gameStatus
            ->resetCurrentGame();

        return $this->getCurrentGame();
    }

    public function getGameList()
    {
        // paginate this in the future
        return GameBasicInfo::collection(Game::all());
    }
}
