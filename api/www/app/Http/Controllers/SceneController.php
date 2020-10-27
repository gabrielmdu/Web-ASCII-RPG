<?php

namespace App\Http\Controllers;

use App\Http\Resources\Scene as SceneResource;
use App\SceneNote;
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

        if (!empty($validatedData['item'])) {
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

        if ($option->getNeedItem() && $option->getNeedItem()->getId() === $itemId) {
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
        return $currScene->getResource($optionIndex);
    }
}
