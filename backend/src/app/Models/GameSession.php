<?php

namespace App\Models;

use App\Enums\GameSessionStatus;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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

    public function currentChoices(): HasManyThrough
    {
        return $this->hasManyThrough(
            Choice::class,
            Scene::class,
            firstKey: 'id',
            localKey: 'current_scene_id'
        )->orderBy('choices.id');
    }

    #[Scope]
    public function active(Builder $query): void
    {
        $query->where('status', GameSessionStatus::ACTIVE);
    }

    #[Scope]
    public function orderByStatus(Builder $query): void
    {
        $sessionOrder = GameSessionStatus::order();

        $whens = '';
        for ($i = 0; $i < count($sessionOrder); $i++) {
            $whens .= " WHEN '$sessionOrder[$i]' THEN " . ($i + 1);
        }

        $elseIndex = count($sessionOrder) + 1;

        $query->orderByRaw("CASE status {$whens} ELSE {$elseIndex} END");
    }
}
