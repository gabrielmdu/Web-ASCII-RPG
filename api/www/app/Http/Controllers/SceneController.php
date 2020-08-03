<?php

namespace App\Http\Controllers;

use App\Exceptions\WarpgException;
use App\GameStatus;
use App\Http\Resources\Scene as SceneResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class SceneController extends Controller
{
    public function getCurrentScene(bool $asResource = true)
    {
        $scene = auth()->user()->getCurrentScene();

        return $asResource ? new SceneResource($scene) : $scene;
    }

    public function setCurrentScene(Request $request)
    {
        $validatedData = $request->validate([
            'option' => 'required|int'
        ]);

        $currScene = $this->getCurrentScene(false);

        try {
            $option = $currScene->options[$validatedData['option']];
        } catch (Throwable $t) {
            throw new WarpgException('Invalid option for scene', Response::HTTP_BAD_REQUEST);
        }

        if (isset($option['need_item'])) {
            if (in_array($option['need_item']['id'], $this->getGameStatus()->items)) {
                $this->getGameStatus()->setCurrentScene($option['destiny']);
            } else {
                throw new WarpgException('The door is locked.', Response::HTTP_OK);
            }
        } else {
            $this->getGameStatus()->setCurrentScene($option['destiny']);
        }
        
        return $this->getCurrentScene();
    }

    public function getGameStatus(): GameStatus
    {
        return auth()
            ->user()
            ->gameStatus;
    }
}
