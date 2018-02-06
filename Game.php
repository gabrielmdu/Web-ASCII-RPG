<?php 
require_once "Scene.php";

final class Game
{
    private $gameStruct;

    private $startingSceneId;
    private $currSceneId;
    private $currScene;
    private $imgDir;
    private $defaultColor;
    private $defaultBackground;

    public function __construct()
    {
        $gameFile = file_get_contents("game.json");
        $this->gameStruct = json_decode($gameFile, true);

        $this->imgDir = $this->gameStruct["adventure"]["image_dir"];
        $this->startingSceneId = $this->gameStruct["story"]["starting_scene"];
        $this->currSceneId = $this->startingSceneId;
        $this->currScene = $this->loadScene($this->currSceneId);
        $this->defaultColor = $this->gameStruct["default_colors"]["color"];
        $this->defaultBackground = $this->gameStruct["default_colors"]["background"];
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

        return new Scene($sceneArray, $this->imgDir, $this->gameStruct["default_colors"]);
    }

    private function drawCurrScene() : string
    {
        return $this->currScene->getStr();
    }

    public function getCurrSceneJSON() : string
    {
        return json_encode([
            "html" => $this->drawCurrScene(),
            "default_colors" => [
                "color" => $this->defaultColor,
                "background" => $this->defaultBackground
            ]
        ]);
    }
}
?>