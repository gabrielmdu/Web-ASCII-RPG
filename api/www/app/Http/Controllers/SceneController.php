<?php

namespace App\Http\Controllers;

use App\GameStatus;
use App\Http\Resources\Scene as SceneResource;
use App\Scene;
use App\SceneNote;
use Illuminate\Http\Request;

class SceneController extends Controller
{
    public function getCurrentScene(bool $asResource = true)
    {
        $scene = auth()->user()->getCurrentScene();

        return $asResource ? new SceneResource($scene) : $scene;
    }

    public function chooseSceneOption(Request $request)
    {
        $validatedData = $request->validate([
            'option' => 'required|int'
        ]);

        $currScene = $this->getCurrentScene(false);
        $option = $currScene->getOption($validatedData['option']);

        switch (Scene::getOptionType($option)) {
            case Scene::OPTION_TYPE_NEED_ITEM:
                if ($this->getGameStatus()->hasItem($option['need_item']['id'])) {
                    $this->getGameStatus()->setCurrentScene($option['destiny']);
                } else {
                    return new SceneNote($option['need_item']['note']);
                }
            break;
            
            case Scene::OPTION_TYPE_ITEM:
                if ($this->getGameStatus()->hasItem($option['item']['id'])) {
                    return new SceneNote($option['item']['with']);
                } else {
                    $this->getGameStatus()->storeItem($validatedData['option'], $option['item']['id']);
                    return new SceneNote($option['item']['without']);
                }

            case Scene::OPTION_TYPE_NOTE:
                return new SceneNote($option['note']);
            
            case Scene::OPTION_TYPE_NORMAL:
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
