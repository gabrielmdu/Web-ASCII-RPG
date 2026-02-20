<?php

namespace App\Models;

use App\Enums\GameSessionStatus;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'game_id',
        'current_scene_id',
    ];

    protected $casts = [
        'status' => GameSessionStatus::class,
        'settings' => 'array',
        'history' => 'array',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player_id');
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function currentScene(): BelongsTo
    {
        return $this->belongsTo(Scene::class, 'current_scene_id');
    }

    #[Scope]
    public function active(Builder $query): void
    {
        $query->where('status', GameSessionStatus::ACTIVE);
    }
}
