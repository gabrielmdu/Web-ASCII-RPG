<?php

namespace App;

use App\Exceptions\WarpgException;
use App\Http\Resources\Scene as SceneResource;
use App\SceneOption;
use Illuminate\Http\Response;
use Jenssegers\Mongodb\Eloquent\Model;
use JsonSerializable;
use Throwable;

class Scene extends Model
{
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function getOption(int $option): SceneOption
    {
        try {
            return new SceneOption($this->options[$option]);
        } catch (Throwable $t) {
            throw new WarpgException('Invalid option for scene '. $t->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function getResource(int $optionIndex): JsonSerializable
    {
        $option = $this->getOption($optionIndex);

        $status = auth()
            ->user()
            ->gameStatus;

        switch ($option->getType()) {
            case SceneOption::OPTION_TYPE_NEED_ITEM:
                $neededItem = $status->getItem($option->getNeedItem()->getId());
                if ($neededItem && $neededItem['used']) {
                    $status->setCurrentScene($option->getDestiny());
                    return new SceneResource($status->getCurrentScene());
                } else {
                    return new SceneNote($option->getNeedItem()->getNote());
                }
            break;

            case SceneOption::OPTION_TYPE_ITEM:
                if ($status->getItem($option->getItem()->getId())) {
                    return new SceneNote($option->getItem()->getWith());
                } else {
                    $status->storeItem($optionIndex, $option->getItem()->getId());
                    return new SceneItem($option->getItem()->getWithout(),
                        $status->game->getItem($option->getItem()->getId()));
                }

            case SceneOption::OPTION_TYPE_NOTE:
                return new SceneNote($option->getNote());

            case SceneOption::OPTION_TYPE_NORMAL:
                $status->setCurrentScene($option->getDestiny());
                return new SceneResource($status->getCurrentScene());
        }
    }
}
