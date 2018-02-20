<?php
require_once "Game.php";

session_start();

//$_SESSION = [];

if (!isset($_SESSION["game"]))
{
    $game = new Game();
    $_SESSION["game"] = serialize($game);
}
else
    $game = unserialize($_SESSION["game"]);

if (isset($_GET["scene_opt"]))
{
    $game->setChosenScene($_GET["scene_opt"]);
    $_SESSION["game"] = serialize($game);
}

echo $game->getCurrSceneJSON();
?>