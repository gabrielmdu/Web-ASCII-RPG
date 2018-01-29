<?php
function createImgLine(int $length, string $char, string $initial, string $final) : string
{
    return $initial . str_repeat($char, $length) . $final . "\n";
}

function writeInMiddle(string $line, string $text) : string
{
    $textWidth = strlen($text);
    $halfLineWidth = strlen($line) / 2;
    $halfTextWidth = $textWidth / 2;
    $startPosition = $halfLineWidth - $halfTextWidth;

    return substr_replace($line, $text, $startPosition, $textWidth);
}

function createScreenHeader(string $title, int $imgWidth) : string
{
    $imgH1 = createImgLine($imgWidth, "-", "**", "**");
    
    $imgH2 = createImgLine($imgWidth, " ", "**", "**");
    $imgH2 = writeInMiddle($imgH2, ">> " . $title . " <<");
    
    $imgH3 = createImgLine($imgWidth, "-", "*-", "-*");

    return $imgH1 . $imgH2 . $imgH3;
} 

function drawScene(string $title) : string
{
    $scene = "";

    $imgFile = file("img/monster_1.txt", FILE_IGNORE_NEW_LINES);
    $imgWidth = strlen($imgFile[0]) + 2;

    $imgHeader = createScreenHeader($title, $imgWidth);

    $scene = $imgHeader;

    foreach ($imgFile as $line) 
    {
        $scene .= "|| " . $line . " ||\n";
    }

    return $scene;
}
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
   <!-- █ █ █ █ -->
   This is a test
   <br>
  
   <div id="main-panel">
       <pre>
<!-- **--------------------------------------------------------------**
**                  <span style="color: orange;">>>>   The Big Fly   <<<</span>                     **
|*--------------------------------------------------------------*|
||                       .-.      .-.                           ||
||                 _..--'`;;`-..-';;'`--.._                     ||
||               .';,    _   `;;'   _    ,;`.                   ||
||              ;;'  `;;' `;.`;;'.;' `;;'  `;;                  ||
||             .;;._.;'`;.   `;;'   .;'`;._.;;.                 ||
||           ;'      '`;;`   `;;'   ';;'`      `;               ||
||          ;:     .:.  `;;. `--' .;;'  .:.     :;              ||
||           ;.   .:|:.     `;;;;'     .:|:.   .;               ||
||            `;  `:|:'    .;;'`;;.    `:|:'  ;'                ||
||             `;. `:'  .;;'.::::.`;;.  `:' .;'                 ||
||               `;._.;;' .:`::::':. `;;._.;'                   ||
||          .::::. `::   (:._.::._.:)   ::' .::::.              ||
||     .:::::'  `::.`:_.--.`:::::. .--._:'.::'  `:::::.         ||
||   .::'     `::    `::-.:::"""":::.-::'   `::      `::.       ||
|| .::'      .::'      | /.^^^..^^^.\ |      `::        `:.     ||
|| :::      .:'::.     \( `;;;..;;;' )/     .::::       :::     ||
|| ::  :  .:':.  `::.   \            /   .::'  .:     .  ::     ||
|| :  ::  .   :     `::.              .::'     :  :   ::  :     ||
|| .:  :    `.::.       `:.          .:'       .::.'    :  :.   ||
|| ::  :  :   : :::.       `:.      .:'       .::: :   :  :  :: ||
|| ::  :        :' `:.       :.    .:       .:' `:        :  :: ||
|| :::     :   ::    `:.      :.  .:      .:'    ::   :     ::: ||
|| ' :       :::'      :.      `::'      .:      `:::       : ` ||
|*--------------------------------------------------------------*|
|| Você encontra uma criatura que lhe arrepia até os ossos. Seu ||
|| olhar é hipnotizante, mas sua mandíbula com dentes afiados   ||
|| é o que mais lhe preocupa. O que fazer?                      ||
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
|| 1) Correr.                                                   ||
|| 2) Enfrentar a criatura.                                     ||
|| 3) Tentar conversar.                                         ||
||--------------------------------------------------------------|| -->
<?php echo drawScene("The Big Fly"); ?>
       </pre>
   </div>
</body>

</html>