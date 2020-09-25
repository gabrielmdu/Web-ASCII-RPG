<?php

namespace App\Http\Controllers;

use App\Http\Resources\Scene as SceneResource;
use App\Scene;
use App\SceneNote;
use App\SceneItem;
use Illuminate\Http\Request;

class SceneController extends Controller
{
    public function getCurrentScene(bool $asResource = true)
    {
        $scene = auth()
            ->user()
            ->gameStatus
            ->getCurrentScene();

        return $asResource ? new SceneResource($scene) : $scene;
    }

    public function chooseSceneOption(Request $request)
    {
        $validatedData = $request->validate([
            'option' => 'required|int',
            'item' => 'nullable|string'
        ]);

        if ($validatedData['item']) {
            return $this->useItem($validatedData['option'], $validatedData['item']);
        } else {
            return $this->getSceneResource($validatedData['option']);
        }
    }

    private function useItem(int $optionIndex, string $itemId)
    {
        $currScene = $this->getCurrentScene(false);
        $option = $currScene->getOption($optionIndex);

        $status = auth()
            ->user()
            ->gameStatus;

        $statusItem = $status->getItem($itemId);
        $gameItem = $status->game->getItem($itemId);
        
        $note = "You can't use {$gameItem['name']} here";
        $data = ['used' => false];
        
        if (isset($option['need_item']['id']) && $option['need_item']['id'] === $itemId) {
            if ($status->useItem($statusItem)) {
                $note = "Used {$gameItem['name']} succesfully";
                $data = ['used' => true];
            }
        }

        return new SceneNote($note, $data);
    }

    private function getSceneResource(int $optionIndex)
    {
        $currScene = $this->getCurrentScene(false);
        $option = $currScene->getOption($optionIndex);

        $status = auth()
            ->user()
            ->gameStatus;

        switch (Scene::getOptionType($option)) {
            case Scene::OPTION_TYPE_NEED_ITEM:
                $neededItem = $status->getItem($option['need_item']['id']);
                if ($neededItem && $neededItem['used']) {
                    $status->setCurrentScene($option['destiny']);
                } else {
                    return new SceneNote($option['need_item']['note']);
                }
            break;
            
            case Scene::OPTION_TYPE_ITEM:
                if ($status->getItem($option['item']['id'])) {
                    return new SceneNote($option['item']['with']);
                } else {
                    $status->storeItem($optionIndex, $option['item']['id']);
                    return new SceneItem($option['item']['without'],
                        $status->game->getItem($option['item']['id']));
                }

            case Scene::OPTION_TYPE_NOTE:
                return new SceneNote($option['note']);
            
            case Scene::OPTION_TYPE_NORMAL:
                $status->setCurrentScene($option['destiny']);
        }
        
        return $this->getCurrentScene();
    }
}
