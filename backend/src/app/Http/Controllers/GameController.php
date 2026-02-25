<?php

namespace App\Http\Controllers;

use App\Http\Resources\GameResource;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $games = Game::with('creator')->withCount('scenes');

        $user = Auth::guard('sanctum')->user();

        if ($user) {
            $games->withUserSessions($user->id);
        } else {
            $games->public();
        }

        return GameResource::collection($games->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game)
    {
        $game = $game->loadMissing('creator')
            ->loadCount('scenes');

        $user = Auth::guard('sanctum')->user();

        if ($user) {
            $game->loadUserSession($user->id);
        }

        return new GameResource($game);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Game $game)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game)
    {
        //
    }
}
