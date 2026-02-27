<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameSessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'player' => new UserResource($this->whenLoaded('player')),
            'game' => new GameResource($this->whenLoaded('game')),
            'status' => $this->status,
            'currentScene' => new SceneResource($this->whenLoaded('currentScene')),
            'settings' => $this->settings,
            'history' => $this->history,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
