<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Game extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'adventure' => $this->adventure,
            'player_items' => $this->getStatusItems()
        ];
    }

    private function getStatusItems(): array
    {
        $items = [];
        foreach (auth()->user()->gameStatus->items as $item) {
            $item = $this->getItem($item['id']);
            $items[] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'description' => $item['description'],
            ];
        }

        return $items;
    }
}
