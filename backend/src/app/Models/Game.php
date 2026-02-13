<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Game extends Model
{
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
}
