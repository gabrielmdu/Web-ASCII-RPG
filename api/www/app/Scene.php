<?php

namespace App;

use App\Exceptions\WarpgException;
use Illuminate\Http\Response;
use Jenssegers\Mongodb\Eloquent\Model;
use Throwable;

class Scene extends Model
{
    const OPTION_TYPE_NORMAL = 'normal';
    const OPTION_TYPE_ITEM = 'item';
    const OPTION_TYPE_NEED_ITEM = 'need_item';
    const OPTION_TYPE_NOTE = 'note';

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function getOption(int $option): array
    {
        try {
            return $this->options[$option];
        } catch (Throwable $t) {
            throw new WarpgException('Invalid option for scene', Response::HTTP_BAD_REQUEST);
        }
    }

    public static function getOptionType(array $option): string
    {
        if (isset($option['need_item'])) {
            return self::OPTION_TYPE_NEED_ITEM;
        } else if (isset($option['item'])) {
            return self::OPTION_TYPE_ITEM;
        } else if (isset($option['note'])) {
            return self::OPTION_TYPE_NOTE;
        } else if (isset($option['destiny'])){
            return self::OPTION_TYPE_NORMAL;
        } else {
            throw new WarpgException('Invalid option type', Response::HTTP_BAD_REQUEST);
        }
    }
}
