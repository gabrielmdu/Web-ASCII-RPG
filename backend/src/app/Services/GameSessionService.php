<?php

namespace App\Services;

use App\Models\Game;
use App\Models\GameSession;
use App\Models\User;

class GameSessionService
{
    public function createGameSession(User $user, int $gameId): GameSession
    {
        $game = Game::with('startScene')->findOrFail($gameId);

        return $user->gameSessions()
            ->create([
                'player_id' => $user->id,
                'game_id' => $game->id,
                'current_scene_id' => $game->startScene->id,
            ]);
    }
}
