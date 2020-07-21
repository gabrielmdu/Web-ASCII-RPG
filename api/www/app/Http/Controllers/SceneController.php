<?php

namespace App\Http\Controllers;

use App\Http\Resources\Scene as SceneResource;
use Illuminate\Http\Request;

class SceneController extends Controller
{
    public function getCurrentScene()
    {
        return new SceneResource(auth()->user()->getCurrentScene());
    }

    public function setCurrentScene(Request $request)
    {
        $validatedData = $request->validate([
            'scene_id' => 'required|string'
        ]);

        auth()->user()->gameStatus->setCurrentScene($validatedData['scene_id']);

        return $this->getCurrentScene();
    }
}
