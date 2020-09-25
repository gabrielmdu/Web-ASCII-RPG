<?php

namespace App\Http\Controllers;

use App\Game;
use App\Http\Resources\Game as GameResource;
use App\Http\Resources\GameBasicInfo;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function getCurrentGame()
    {
        $game = auth()
            ->user()
            ->gameStatus
            ->game;

        return new GameResource($game);
    }

    public function setCurrentGame(Request $request)
    {
        $validatedData = $request->validate([
            'game_id' => 'required|string',
        ]);

        auth()
            ->user()
            ->gameStatus
            ->setCurrentGame($validatedData['game_id']);
        
        return $this->resetCurrentGame();
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
