<?php

use App\Game;
use App\Scene;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class AdventureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (new DirectoryIterator(database_path('seeds/adventures')) as $dir) {
            if ($dir->isDot()) {
                continue;
            }

            if (!$dir->isDir()) {
                continue;
            }

            $this->createAdventure($dir);
        }
    }

    private function createAdventure(string $advDir)
    {
        $advStr = File::get(database_path("seeds/adventures/{$advDir}/{$advDir}.json"));
        $gameArr = json_decode($advStr, true);

        $scenesDb = [];

        foreach ($gameArr['scenes'] as $scene) {
            if (is_string($scene['image'])) {
                $img = File::get(database_path("seeds/adventures/{$advDir}/{$scene['image']}"));
                $scene['image'] = [$img];
            } else if (is_array($scene['image'])) {
                foreach ($scene['image'] as $key => $sceneImg) {
                    $img = File::get(database_path("seeds/adventures/{$advDir}/{$sceneImg}"));
                    $scene['image'][$key] = $img;
                }
            }

            $scenesDb[] = Scene::create($scene);
        }

        unset($gameArr['scenes']);
        $gameDb = Game::create($gameArr);
        $gameDb->scenes()->saveMany($scenesDb);
    }
}
