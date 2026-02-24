<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Game extends Model
{
    use HasFactory;

    protected $casts = [
        'public' => 'bool',
        'settings' => 'array',
    ];

    protected $fillable = [
        'creator_id',
        'name',
        'description',
        'version',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Game $game) {
            $game->slug = Str::slug($game->name);
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function scenes(): HasMany
    {
        return $this->hasMany(Scene::class);
    }

    public function startScene(): HasOne
    {
        return $this->scenes()
            ->one()
            ->where('type', 'start');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(GameSession::class);
    }

    #[Scope]
    protected function public(Builder $query): void
    {
        $query->where('public', true);
    }

    #[Scope]
    protected function withUserSessions(Builder $query, int $userId): void
    {
        $query->with([
            'sessions' => fn($q) => $q->where('player_id', $userId)
        ]);
    }
}
