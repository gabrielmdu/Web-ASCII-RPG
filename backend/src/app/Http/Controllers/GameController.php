<?php

namespace App\Http\Controllers;

use App\Enums\GameSearchSort;
use App\Http\Requests\GameIndexRequest;
use App\Http\Resources\GameResource;
use App\Models\Game;
use App\Services\GameService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GameIndexRequest $request, GameService $service)
    {
        $games = $service->search(
            Auth::guard('sanctum')->user(),
            $request->search,
            $request->enum('sort', GameSearchSort::class),
            $request->boolean('public'),
            $request->boolean('asc', true),
        );

        return GameResource::collection(
            $games->paginate(10)
                ->withQueryString()
        )->response();
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
            $game->loadUserSessions($user->id);
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
