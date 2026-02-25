<?php

namespace Database\Factories;

use App\Enums\GameSessionStatus;
use App\Models\Game;
use App\Models\Scene;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameSession>
 */
class GameSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'player_id' => User::factory(),
            'game_id' => Game::factory(),
            'current_scene_id' => function (array $attributes): int {
                return Scene::factory()->create([
                    'game_id' => $attributes['game_id'],
                ])->id;
            },
            'status' => GameSessionStatus::ACTIVE,
            'settings' => '{}',
            'history' => '{}',
        ];
    }

    public function active(): static
    {
        return $this->state(fn() => ['status' => GameSessionStatus::ACTIVE]);
    }

    public function abandoned(): static
    {
        return $this->state(fn() => ['status' => GameSessionStatus::ABANDONED]);
    }

    public function finished(): static
    {
        return $this->state(fn() => ['status' => GameSessionStatus::FINISHED]);
    }
}
