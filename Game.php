<?php 
require_once "Scene.php";

final class Game
{
    private $gameStruct;

    private $startingSceneId;
    private $currSceneId;
    private $currScene;
    private $imgDir;
    private $imgHSpaces;

    public function __construct()
    {
        $gameFile = file_get_contents("game.json");
        $this->gameStruct = json_decode($gameFile, true);

        $this->imgDir = $this->gameStruct["adventure"]["image_dir"];
        $this->imgHSpaces = $this->gameStruct["adventure"]["image_h_spaces"];
        $this->startingSceneId = $this->gameStruct["story"]["starting_scene"];
        $this->currSceneId = $this->startingSceneId;
        $this->currScene = $this->loadScene($this->currSceneId);
    }

    public function setChosenScene($id)
    {
        $this->currSceneId = $id === "start" ? 
            $this->startingSceneId : 
            $this->currSceneId = $this->currScene->getOptions()[$id]->getDestiny();
        
        $this->currScene = $this->loadScene($this->currSceneId);
    }

    private function loadScene($id) : Scene
    {
        $sceneArray = array_filter($this->gameStruct["story"]["scenes"], function ($scene) use($id) { return $scene["id"] === $id; });
        $sceneArray = reset($sceneArray);

        return new Scene($sceneArray, $this->imgDir, $this->imgHSpaces, $this->gameStruct["default_colors"]);
    }

    private function drawCurrScene() : string
    {
        return $this->currScene->getStr();
    }

    private function getCurrSceneColors() : array
    {
        return $this->currScene->getColors();
    }

    public function getCurrSceneJSON() : string
    {
        return json_encode([
            "html" => $this->drawCurrScene(),
            "colors" => $this->getCurrSceneColors()
        ]);
    }
}
?>