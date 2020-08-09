<?php

namespace App;

use JsonSerializable;

class SceneItem implements JsonSerializable
{
    /** @var string Note text */
    private $note;

    /** @var array Item attributes */
    private $attributes;

    public function __construct(string $note, array $attributes)
    {
        $this->note = $note;
        $this->attributes = $attributes;
    }

    public function jsonSerialize() {
        return $this->toArray();
    }

    public function toArray()
    {
        return [
            'resource_type' => 'item',
            'note' => $this->note,
            'attributes' => [
                'id' => $this->attributes['id'],
                'name' => $this->attributes['name'],
                'description' => $this->attributes['description'],
            ]
        ];
    }
}