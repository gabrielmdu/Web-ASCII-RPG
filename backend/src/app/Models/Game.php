<?php

namespace App\Models;

use App\Enums\GameSearchSort;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Game extends Model
{
    use HasFactory, SoftDeletes;

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
            $game->slug = static::generateUniqueSlug($game->name);
        });
    }

    /**
     * Uses the slug field instead of id for the routes.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
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

    /**
     * Eager load user sessions for the game.
     */
    public function loadUserSessions(int $userId): Game
    {
        return $this->load(
            [
                'sessions' => fn($q) => $q->where('player_id', $userId),
                'sessions.currentScene.choices'
            ]
        );
    }

    /**
     * Scope query to public games only.
     */
    #[Scope]
    protected function public(Builder $query): void
    {
        $query->where('public', true);
    }

    /**
     * Scope query to eager load user sessions for the Game collection.
     */
    #[Scope]
    protected function withUserSessions(Builder $query, int $userId): void
    {
        $query->with([
            'sessions' => fn($q) => $q->where('player_id', $userId)
        ]);
    }

    /**
     * Scope query to search games.
     */
    #[Scope]
    protected function searchString(Builder $query, ?string $search): void
    {
        $query->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('games.name', 'like', "%{$search}%")
                    ->orWhere('games.description', 'like', "%{$search}%")
                    ->orWhereHas('creator', function ($userQuery) use ($search) {
                        $userQuery->where('users.name', 'like', "%{$search}%");
                    });
            });
        });
    }

    /**
     * Scope query to order games by a sort string.
     */
    #[Scope]
    protected function orderBySort(Builder $query, GameSearchSort $sort, bool $asc = true): void
    {
        $direction = $asc ? 'asc' : 'desc';

        match ($sort) {
            GameSearchSort::CREATOR_NAME => $query
                ->leftJoin('users', 'games.creator_id', '=', 'users.id')
                ->select('games.*')
                ->orderBy('users.name', $direction),

            default => $query->orderBy($sort->value, $direction),
        };
    }

    private static function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name) . '-' . uniqid();

        while (static::where('slug', $slug)->exists()) {
            $slug = Str::slug($name) . '-' . uniqid();
        }

        return $slug;
    }
}
