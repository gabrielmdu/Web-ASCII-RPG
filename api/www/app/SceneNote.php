<?php

namespace App;

use JsonSerializable;

class SceneNote implements JsonSerializable
{
    /** @var string Note text */
    private $text;

    /** @var array Note additional data */
    private $data;

    public function __construct(string $text, array $data = [])
    {
        $this->text = $text;
        $this->data = $data;
    }

    public function jsonSerialize() {
        return $this->toArray();
    }

    public function toArray()
    {
        $arr = [
            'resource_type' => 'note',
            'text' => $this->text
        ];

        if ($this->data) {
            $arr['data'] = $this->data;
        }

        return $arr;
    }
}