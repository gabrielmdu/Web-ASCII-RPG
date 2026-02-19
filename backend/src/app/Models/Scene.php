<?php

namespace App\Models;

use App\Enums\SceneType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scene extends Model
{
    protected $casts = [
        'type' => SceneType::class,
        'media' => 'array',
        'settings' => 'array',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function choices(): HasMany
    {
        return $this->hasMany(Choice::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(GameSession::class, 'current_scene_id');
    }
}
