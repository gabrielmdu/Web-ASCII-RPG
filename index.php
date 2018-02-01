<?php
require_once "Game.php";

session_start();

$game = new Game();
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">

   <title>Document</title>

   <link href="https://fonts.googleapis.com/css?family=VT323" rel="stylesheet">
   <link rel="stylesheet" href="css/main.css">
   <!-- <script src="main.js"></script> -->
</head>

<body>
  
    <div id="main-panel">
        <pre><?php echo $game->draw(); ?></pre>
    </div>

</body>

</html>