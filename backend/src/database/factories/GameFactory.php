<?php

namespace Database\Factories;

use App\Models\Scene;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(3, true) . ' Adventure';

        return [
            'creator_id' => User::factory(),
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'description' => fake()->sentence(10),
            'version' => '1',
            'public' => true,
            'settings' => '{}',
        ];
    }

    public function private(): static
    {
        return $this->state(fn() => ['public' => false]);
    }

    public function withScenes(int $count = 3): static
    {
        // there should be at least 3 scenes in a game
        $count = $count < 3 ? 3 : $count;
        // view count is total scenes minus start and end scenes
        $totalViewScenes = $count - 2;

        return $this->has(
            Scene::factory()
                ->start()
                ->withChoices()
        )->has(
            Scene::factory()
                ->view()
                ->count($totalViewScenes)
                ->withChoices()
        )->has(Scene::factory()->end());
    }
}
