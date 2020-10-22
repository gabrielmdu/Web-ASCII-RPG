<?php

namespace App;

use App\Exceptions\WarpgException;
use Illuminate\Http\Response;

class SceneOption
{
    use ArrayToObjectTrait;

    const OPTION_TYPE_NORMAL = 'normal';
    const OPTION_TYPE_ITEM = 'item';
    const OPTION_TYPE_NEED_ITEM = 'need_item';
    const OPTION_TYPE_NOTE = 'note';

    public function __construct(array $option)
    {
        $this->data = $option;
    }

    /**
     * Returns the option type based on its data
     *
     * @return string The option type
     */
    public function getType(): string
    {
        if (isset($this->data['need_item'])) {
            return self::OPTION_TYPE_NEED_ITEM;
        } else if (isset($this->data['item'])) {
            return self::OPTION_TYPE_ITEM;
        } else if (isset($this->data['note'])) {
            return self::OPTION_TYPE_NOTE;
        } else if (isset($this->data['destiny'])){
            return self::OPTION_TYPE_NORMAL;
        } else {
            throw new WarpgException('Invalid option type', Response::HTTP_BAD_REQUEST);
        }
    }
}