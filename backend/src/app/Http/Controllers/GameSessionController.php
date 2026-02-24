<?php

namespace App\Http\Controllers;

use App\Http\Requests\SelectTargetGameSessionRequest;
use App\Http\Requests\StoreGameSessionRequest;
use App\Http\Resources\GameSessionResource;
use App\Models\GameSession;
use App\Services\GameSessionService;
use Illuminate\Http\Request;

class GameSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sessions = $request->user()
            ->gameSessions()
            ->orderByStatus()
            ->latest('updated_at')
            ->paginate(10);

        return GameSessionResource::collection($sessions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGameSessionRequest $request, GameSessionService $service)
    {
        $user = $request->user();
        $session = $service->createGameSession($user, $request->validated('gameId'));

        return new GameSessionResource($session)
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(GameSession $session)
    {
        return new GameSessionResource($session);
    }

    /**
     * Select a choice from the current session's scene.
     */
    public function selectTarget(
        SelectTargetGameSessionRequest $request,
        GameSessionService $service,
        GameSession $session
    ) {
        $service->selectTarget($session, $request->validated('choiceIndex'));
        return response()->noContent();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GameSession $session)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GameSession $session)
    {
        //
    }
}
