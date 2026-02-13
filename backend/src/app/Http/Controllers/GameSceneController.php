<?php

namespace App\Http\Controllers;

use App\Http\Resources\SceneResource;
use App\Models\Game;
use App\Models\Scene;
use Illuminate\Http\Request;

class GameSceneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Game $game)
    {
        $scenes = $game->scenes()
            ->with('choices')
            ->paginate();

        return SceneResource::collection($scenes);
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
    public function show(Game $game, Scene $scene)
    {
        return new SceneResource($scene);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Scene $scene)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scene $scene)
    {
        //
    }
}
