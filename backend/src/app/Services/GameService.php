<?php

namespace App\Services;

use App\Enums\GameSearchSort;
use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class GameService
{
    public function search(
        ?User $user,
        ?string $search,
        ?GameSearchSort $sort,
        ?bool $public,
        ?bool $asc = true,
    ): Builder {
        $sort ??= GameSearchSort::ID;
        $asc ??= true;

        $games = Game::with('creator')->withCount('scenes')
            ->searchString($search)
            ->orderBySort($sort, $asc);

        if ($user) {
            $games->withUserSessions($user->id);
        }

        if (!$user || $public) {
            $games->public();
        }

        return $games;
    }
}
