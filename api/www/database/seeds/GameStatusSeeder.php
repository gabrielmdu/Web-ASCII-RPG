<?php

use App\Game;
use App\GameStatus;
use App\User;
use Illuminate\Database\Seeder;

class GameStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();
        $game = Game::first();

        GameStatus::create([
            'user_id' => $user->id,
            'game_id' => $game->id,
            'scene' => $game->starting_scene,
            'items' => []
        ]);
    }
}
