<?php
function wrapDOMTagInLine(string $line, string $element, string $params, int $position) : string
{
    $str = substr_replace($line, "<" . $element . $params . ">", $position, 0);
    $str = substr_replace($str, "</" . $element . ">", (-$position - 1), 0);
    
    return $str;
}

function createSceneLine(int $length, string $char, string $initial, string $final) : string
{
    return $initial . str_repeat($char, $length) . $final . "\n";
}

function createMiddleTextLine(string $line, string $text) : string
{
    $textWidth = strlen($text);
    $halfLineWidth = strlen($line) / 2;
    $halfTextWidth = $textWidth / 2;
    $startPosition = $halfLineWidth - $halfTextWidth;

    return substr_replace($line, $text, $startPosition, $textWidth);
}

function createSceneHeader(string $title, int $imgWidth) : string
{
    $imgH1 = createSceneLine($imgWidth, "-", "**", "**");
    
    $imgH2 = createSceneLine($imgWidth, " ", "**", "**");
    $imgH2 = createMiddleTextLine($imgH2, ">> " . $title . " <<");
    $imgH2 = wrapDOMTagInLine($imgH2, "span", " class='scene-title'", 2);

    $imgH3 = createSceneLine($imgWidth, "-", "|*", "*|");

    return $imgH1 . $imgH2 . $imgH3;
}

function createSceneImage(array $imgFile, string $initial, string $final) : string
{
    $img = "";

    foreach ($imgFile as $line)
    {
        $imgLine = $initial . $line . $final . "\n";
        $imgLine = wrapDOMTagInLine($imgLine, "span", " class='scene-img'", 2);

        $img .= $imgLine;
    }

    return $img;
}

function createSceneText(string $text, int $imgWidth) : string
{
    $str = createSceneLine($imgWidth, "-", "|*", "*|");

    $lines = explode("\0", wordwrap($text, $imgWidth, "\0"));

    foreach ($lines as $line)
    {
        $textLine = "|| " 
                  . $line 
                  . str_repeat(" ", ($imgWidth - strlen(utf8_decode($line))) - 1) 
                  . "||\n";

        $textLine = wrapDOMTagInLine($textLine, "span", " class='scene-text'", 2);

        $str .= $textLine;
    }

    $str .= createSceneLine($imgWidth, "~", "~~", "~~");

    return $str;
}

function createSceneOptions(array $options, int $imgWidth) : string
{
    $str = "";

    foreach ($options as $i => $opt) {
        $optWithIndex = ($i + 1) . ") " . $opt;

        $lines = explode("\0", wordwrap($optWithIndex, $imgWidth, "\0"));

        foreach ($lines as $line)
        {
            $optLine = "|| " 
                     . $line 
                     . str_repeat(" ", ($imgWidth - strlen(utf8_decode($line))) - 1)
                     . "||\n";
        
            $optLine = wrapDOMTagInLine($optLine, "span", " class='scene-option'", 2);

            $str .= $optLine;
        }
    }

    $str .= createSceneLine($imgWidth, "-", "**", "**"); 

    return $str;
}

function drawScene(string $title) : string
{
    $imgFile = file("img/monster_1.txt", FILE_IGNORE_NEW_LINES);
    $imgWidth = strlen($imgFile[0]) + 2;

    $scene = createSceneHeader($title, $imgWidth) 
           . createSceneImage($imgFile, "|| ", " ||")
           . createSceneText("Você encontra uma criatura que lhe arrepia até os ossos. "
                           . "Seu olhar é hipnotizante, mas sua mandíbula com dentes afiados " 
                           . "é o que mais lhe preocupa. O que fazer?", $imgWidth)
           . createSceneOptions(["Correr.",
                                 "Enfrentar a criatura, apesar de sua aparente vantagem física, mental e monetária.",
                                 "Desistir da vida, afinal não existem chances contra tal ser medonho nesse planeta. Partiu morrer.",
                                 "Tentar conversar."], 
                                 $imgWidth);

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
  
    <div id="main-panel">
        <pre><?php echo drawScene("The Big Fly"); ?></pre>
    </div>

</body>

</html>