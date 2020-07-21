<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Car extends Model
{
    public $guarded = [];
    
    public static function getByYear(int $year)
    {
        return self::where('year', $year)->get();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
