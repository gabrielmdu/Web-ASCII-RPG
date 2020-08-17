<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isGuest()
    {
        return $this->guest;
    }

    public function gameStatus()
    {
        return $this->hasOne(GameStatus::class);
    }

    public function getCurrentGame()
    {
        return Game::find($this->gameStatus->game_id);
    }

    public function getCurrentScene()
    {
        return $this->getCurrentGame()->getScene($this->gameStatus->scene);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'user_name' => $this->name,
            'guest' => $this->guest
        ];
    }
}
