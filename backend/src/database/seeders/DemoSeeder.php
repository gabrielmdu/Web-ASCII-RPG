<?php

namespace Database\Seeders;

use App\Enums\SceneType;
use App\Models\Choice;
use App\Models\Game;
use App\Models\GameSession;
use App\Models\Scene;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Json;

class DemoSeeder extends Seeder
{
    use WithoutModelEvents;

    private function createGame(int $creatorId, array $data): Game
    {
        return Game::create([
            'creator_id' => $creatorId,
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'],
            'version' => $data['version'],
            'settings' => $data['settings'],
        ]);
    }

    private function createScene(int $gameId, array $data): Scene
    {
        $path = database_path('seeders/games/demo/media');

        $scene = Scene::create([
            'game_id' => $gameId,
            'slug' => $data['slug'],
            'title' => $data['title'],
            'type' => $data['type'],
            'media' => [
                'image' => array_map(fn($item) => file_get_contents($path . '/' . $item), $data['media']['image']),
                'interval' => $data['media']['interval'] ?? null,
            ],
            'text' => $data['text'],
            'settings' => $data['settings'],
        ]);

        return $scene;
    }

    private function createChoice(int $sceneId, int $targetId, array $data): Choice
    {
        return Choice::create([
            'scene_id' => $sceneId,
            'target_id' => $targetId,
            'text' => $data['text'],
            'settings' => $data['settings'],
        ]);
    }

    private function getScene(array $scenes, string $slug): ?Scene
    {
        foreach ($scenes as $scene) {
            if ($scene->slug === $slug) {
                return $scene;
            }
        }

        return null;
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $master = User::factory()->create([
            'name' => 'Master',
            'email' => 'master@warpg.net',
            'password' => Hash::make('master86'),
        ]);

        $path = database_path('seeders/games/demo');
        $gameData = Json::decode(File::get($path . '/game.json'), true);

        $game = $this->createGame($master->id, $gameData['game']);

        $scenes = [];
        foreach ($gameData['scenes'] as $scene) {
            $scenes[] = $this->createScene($game->id, $scene);
        }

        $choicesData = [];
        foreach ($gameData['scenes'] as $scene) {
            if (!isset($scene['choices'])) {
                continue;
            }

            foreach ($scene['choices'] as $choice) {
                $choicesData[] = array_merge($choice, ['scene' => $scene['slug']]);
            }
        }

        foreach ($choicesData as $choice) {
            $scene = $this->getScene($scenes, $choice['scene']);
            $target = $this->getScene($scenes, $choice['target']);

            if ($scene && $target) {
                $this->createChoice($scene->id, $target->id, $choice);
            }
        }

        GameSession::create([
            'player_id' => 1,
            'game_id' => 1,
            'current_scene_id' => 1,
            'status' => 'active',
        ]);
    }
}
