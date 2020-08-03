<?php

namespace App\Http\Controllers;

use App\Http\Resources\Game as GameResource;
use Illuminate\Http\Request;

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

    public function storeItem(Request $request)
    {
        $validatedData = $request->validate([
            'option' => 'required|int',
            'item_id' => 'required|string'
        ]);

        return auth()
            ->user()
            ->gameStatus
            ->storeItem($validatedData['option'], $validatedData['item_id']);
    }
}
