<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Choice extends Model
{
    protected $casts = [
        'settings' => 'array',
    ];

    public function scene(): BelongsTo
    {
        return $this->belongsTo(Scene::class);
    }

    public function target(): HasOne
    {
        return $this->hasOne(Scene::class, 'target_id');
    }
}
