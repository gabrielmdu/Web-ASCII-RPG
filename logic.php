<?php
require_once "Game.php";

session_start();

if (!isset($_SESSION["game"]))
{
    $game = new Game();
    $_SESSION["game"] = serialize($game);
}
else
    $game = unserialize($_SESSION["game"]);

if (isset($_GET["opt"]))
{
    $game->setChosenScene($_GET["opt"]);
    $_SESSION["game"] = serialize($game);
}

echo $game->draw();
?>