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
            'id' => $this->id,
            'creator' => new UserResource($this->whenLoaded('creator')),
            'name' => $this->name,
            'slug' => $this->slug,
            'isPublic' => $this->public,
            'description' => $this->description,
            'version' => $this->version,
            'lastModified' => $this->last_modified,
            'settings' => $this->settings,
            'sessions' => $this->whenLoaded('sessions'),
            'scenesCount' => $this->whenCounted('scenes'),
            'scenesUrl' => route('game.scenes.index', $this->id),
        ];
    }
}
