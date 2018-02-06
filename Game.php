<?php 
require_once "Scene.php";

final class Game
{
    private $gameStruct;

    private $startingSceneId;
    private $currSceneId;
    private $currScene;
    private $imgDir;

    public function __construct()
    {
        $gameFile = file_get_contents("game.json");
        $this->gameStruct = json_decode($gameFile, true);

        $this->imgDir = $this->gameStruct["adventure"]["img_dir"];
        $this->startingSceneId = $this->gameStruct["story"]["starting_scene"];
        $this->currSceneId = $this->startingSceneId;
        $this->currScene = $this->loadScene($this->currSceneId);
    }

    public function setChosenScene($index)
    {
        $this->currSceneId = $this->currScene->getOptions()[$index]->getDestiny();
        $this->currScene = $this->loadScene($this->currSceneId);
    }

    private function loadScene($id) : Scene
    {
        $sceneArray = array_filter($this->gameStruct["story"]["scenes"], function ($scene) use($id) { return $scene["id"] === $id; });
        $sceneArray = reset($sceneArray);

        return new Scene($sceneArray, $this->imgDir);
    }

    private function drawCurrScene() : string
    {
        return $this->currScene->getStr();
    }

    public function getCurrSceneJSON() : string
    {
        return json_encode([
            "html" => $this->drawCurrScene()
        ]);
    }
}
?>