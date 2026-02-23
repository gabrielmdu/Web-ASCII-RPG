<?php

namespace App\Services;

use App\Enums\GameSessionStatus;
use App\Events\GameSessionCreated;
use App\Models\Game;
use App\Models\GameSession;
use App\Models\User;

class GameSessionService
{
    public function createGameSession(User $user, int $gameId): GameSession
    {
        $game = Game::with('startScene')->findOrFail($gameId);

        $session = $user->gameSessions()
            ->create([
                'player_id' => $user->id,
                'game_id' => $game->id,
                'current_scene_id' => $game->startScene->id,
            ]);

        event(new GameSessionCreated($session));

        return $session;
    }

    public function selectTarget(GameSession $session, int $choiceIndex): void
    {
        // the in-game choice index is 1-based, but should be zero-based here
        $choiceIndex--;

        $session->loadMissing('currentChoices.target');
        $targetScene = $session->currentChoices[$choiceIndex]->target;

        if ($targetScene->isEnd()) {
            $session->status = GameSessionStatus::FINISHED;
        }

        $session->current_scene_id = $targetScene->id;
        $session->save();
    }
}
