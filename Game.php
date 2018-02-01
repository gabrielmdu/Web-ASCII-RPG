<?php 
final class Game
{
    private $gameStruct;

    private $startingScene;
    private $imgDir;

    public function __construct()
    {
        $gameFile = file_get_contents("game.json");
        $this->gameStruct = json_decode($gameFile, true);

        $this->imgDir = $this->gameStruct["adventure"]["img_dir"];
        $this->startingScene = $this->gameStruct["story"]["starting_scene"];
    }

    private function wrapDOMTagInLine(string $line, string $element, string $params, int $position) : string
    {
        $str = substr_replace($line, "<" . $element . $params . ">", $position, 0);
        $str = substr_replace($str, "</" . $element . ">", (-$position - 1), 0);
        
        return $str;
    }

    private function createSceneLine(int $length, string $char, string $initial, string $final) : string
    {
        return $initial . str_repeat($char, $length) . $final . "\n";
    }

    private function createMiddleTextLine(string $line, string $text) : string
    {
        $textWidth = strlen($text);
        $halfLineWidth = strlen($line) / 2;
        $halfTextWidth = $textWidth / 2;
        $startPosition = $halfLineWidth - $halfTextWidth;

        return substr_replace($line, $text, $startPosition, $textWidth);
    }

    private function createSceneHeader(string $title, int $imgWidth) : string
    {
        $imgH1 = $this->createSceneLine($imgWidth, "-", "**", "**");
        
        $imgH2 = $this->createSceneLine($imgWidth, " ", "**", "**");
        $imgH2 = $this->createMiddleTextLine($imgH2, ">> " . $title . " <<");
        $imgH2 = $this->wrapDOMTagInLine($imgH2, "span", " class='scene-title'", 2);

        $imgH3 = $this->createSceneLine($imgWidth, "-", "|*", "*|");

        return $imgH1 . $imgH2 . $imgH3;
    }

    private function createSceneImage(array $imgFile, int $imgWidth, string $initial, string $final) : string
    {
        $img = "";

        foreach ($imgFile as $line)
        {
            $lineLeft = ($imgWidth - 2) - strlen(utf8_decode($line));

            $imgLine = $initial . $line . str_repeat(" ", $lineLeft) . $final . "\n";
            $imgLine = $this->wrapDOMTagInLine($imgLine, "span", " class='scene-img'", 2);

            $img .= $imgLine;
        }

        return $img;
    }

    private function createSceneText(string $text, int $imgWidth) : string
    {
        $str = $this->createSceneLine($imgWidth, "-", "|*", "*|");

        $lines = explode("\0", wordwrap($text, $imgWidth - 1, "\0"));

        foreach ($lines as $line)
        {
            $textLine = "|| " 
                    . $line 
                    . str_repeat(" ", ($imgWidth - strlen(utf8_decode($line))) - 1) 
                    . "||\n";

            $textLine = $this->wrapDOMTagInLine($textLine, "span", " class='scene-text'", 2);

            $str .= $textLine;
        }

        $str .= $this->createSceneLine($imgWidth, "~", "~~", "~~");

        return $str;
    }

    private function createSceneOptions(array $options, int $imgWidth) : string
    {
        $str = "";

        foreach ($options as $optIndex => $opt) {
            $optWithIndex = ($optIndex + 1) . ") " . $opt["text"];

            $lines = explode("\0", wordwrap($optWithIndex, $imgWidth, "\0"));

            foreach ($lines as $line)
            {
                $optLine = "|| " 
                        . $line 
                        . str_repeat(" ", ($imgWidth - strlen(utf8_decode($line))) - 1)
                        . "||\n";
            
                $optLine = $this->wrapDOMTagInLine($optLine, "span", " class='scene-option' data-id='" . $optIndex . "'", 2);

                $str .= $optLine;
            }
        }

        $str .= $this->createSceneLine($imgWidth, "-", "**", "**"); 

        return $str;
    }

    private function drawScene($id) : string
    {
        $sceneArray = array_filter($this->gameStruct["story"]["scenes"], function ($currScene) use($id) { return $currScene["id"] === $id ; });
        $scene = reset($sceneArray);

        $imgFile = file($this->imgDir . "/" . $scene["image"], FILE_IGNORE_NEW_LINES);
        $imgWidth = max(array_map("strlen", $imgFile)) + 2;

        $sceneStr = $this->createSceneHeader($scene["title"], $imgWidth) 
                  . $this->createSceneImage($imgFile, $imgWidth, "|| ", " ||")
                  . $this->createSceneText($scene["text"], $imgWidth)
                  . (array_key_exists("options", $scene) ? $this->createSceneOptions($scene["options"], $imgWidth) : "");

        return $sceneStr;
    }

    public function draw()
    {
        return $this->drawScene($this->startingScene);
    }
}
?>