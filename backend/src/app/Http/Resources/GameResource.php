<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'creator' => new CreatorResource($this->whenLoaded('creator')),
            'name' => $this->name,
            'slug' => $this->slug,
            'isPublic' => $this->public,
            'description' => $this->description,
            'version' => $this->version,
            'createdAt' => $this->created_at,
            'lastModified' => $this->last_modified,
            'settings' => $this->settings,
            'sessions' => GameSessionResource::collection($this->whenLoaded('sessions')),
            'scenesCount' => $this->whenCounted('scenes'),
            'scenesUrl' => route('game.scenes.index', $this->slug),
        ];
    }
}
