<?php

namespace App;

use JsonSerializable;

class SceneNote implements JsonSerializable
{
    /** @var string Note text */
    private $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function jsonSerialize() {
        return $this->toArray();
    }

    public function toArray()
    {
        return [
            'resource_type' => 'note',
            'text' => $this->text
        ];
    }
}