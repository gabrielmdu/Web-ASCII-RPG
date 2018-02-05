<?php 
require_once "Scene.php";

final class Game
{
    private $gameStruct;

    private $startingScene;
    private $currScene;
    private $imgDir;
    private $scenes;

    public function __construct()
    {
        $gameFile = file_get_contents("game.json");
        $this->gameStruct = json_decode($gameFile, true);

        $this->imgDir = $this->gameStruct["adventure"]["img_dir"];
        $this->startingScene = $this->gameStruct["story"]["starting_scene"];
        $this->currScene = $this->startingScene;
        $this->scenes = [];

        $this->loadScenes();
    }

    private function loadScenes()
    {
        foreach($this->gameStruct["story"]["scenes"] as $sceneArray)
        {
            $s = new Scene($sceneArray, $this->imgDir);
            array_push($this->scenes, $s);
        }    
    }

    private function getScene($id) : Scene
    {
        $sceneArray = array_filter($this->scenes, function ($currScene) use($id) { return $currScene->getId("id") === $id; });
        return reset($sceneArray);
    }

    private function drawScene($id) : string
    {
        return $this->getScene($id)->getStr();
    }

    public function draw()
    {
        return $this->drawScene($this->currScene);
    }
}
?>