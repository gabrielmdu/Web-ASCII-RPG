<?php

namespace Database\Factories;

use App\Enums\SceneType;
use App\Models\Choice;
use App\Models\Game;
use App\Models\Scene;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Scene>
 */
class SceneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->words(2, true);

        return [
            'game_id' => Game::factory(),
            'slug' => Str::slug($title),
            'title' => ucwords($title),
            'type' => SceneType::VIEW,
            'media' => '{}',
            'text' => fake()->sentence(10),
            'settings' => '{}',
        ];
    }

    public function view(): static
    {
        return $this->state(fn() => ['type' => SceneType::VIEW]);
    }

    public function start(): static
    {
        return $this->state(fn() => ['type' => SceneType::START]);
    }

    public function end(): static
    {
        return $this->state(fn() => ['type' => SceneType::END]);
    }

    public function withChoices(int $count = 2, bool $isRandom = false): static
    {
        if ($isRandom) {
            $count = mt_rand(1, $count);
        }

        return $this->afterCreating(function (Scene $scene) use ($count) {
            Choice::factory()
                ->count($count)
                ->for($scene)
                // for now the target is the same as the scene
                ->for($scene, 'target')
                ->create();
        });
    }
}
