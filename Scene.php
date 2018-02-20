<?php
require_once "Option.php";

class Scene
{
    private $id;
    private $imgFile;
    private $imgHSpaces;
    private $imgWidth;
    private $type;
    private $title;
    private $text;
    private $str;
    private $options;
    private $defaultColors;
    private $sceneColors; 

    public function __construct(array $sceneArray, string $imgDir, int $imgHSpaces, array $defaultColors)
    {
        $this->id = $sceneArray["id"];
        $this->imgFile = file($imgDir . "/" . $sceneArray["image"], FILE_IGNORE_NEW_LINES);
        $this->imgHSpaces = $imgHSpaces;
        $this->imgWidth = max(array_map("strlen", $this->imgFile)) + ($this->imgHSpaces * 2);
        $this->type = $sceneArray["type"];
        $this->title = $sceneArray["title"];
        $this->text = $sceneArray["text"];
        $this->options = [];
        $this->defaultColors = $defaultColors;
        $this->sceneColors = $sceneArray["colors"] ?? null;

        if (array_key_exists("options", $sceneArray))
            foreach($sceneArray["options"] as $opt)
                $this->options[] = new Option($opt["destiny"], $opt["text"]);

        $this->str = $this->createHeader()
                   . $this->createImage()
                   . $this->createText()
                   . $this->createOptions();
    }

    private function wrapDOMTagInLine(string $line, string $element, string $params, int $position) : string
    {
        $str = substr_replace($line, "<" . $element . $params . ">", $position, 0);
        $str = substr_replace($str, "</" . $element . ">", (-$position - 1), 0);
        
        return $str;
    }

    private function createLine(int $length, string $char, string $initial, string $final) : string
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

    private function createHeader() : string
    {
        $imgH1 = $this->createLine($this->imgWidth, "-", "**", "**");
        
        $imgH2 = $this->createLine($this->imgWidth, " ", "**", "**");
        $imgH2 = $this->createMiddleTextLine($imgH2, ">> " . $this->title . " <<");
        $imgH2 = $this->wrapDOMTagInLine($imgH2, "span", " class='scene-title'", 2);

        $imgH3 = $this->createLine($this->imgWidth, "-", "|*", "*|");

        return $imgH1 . $imgH2 . $imgH3;
    }

    private function createImage() : string
    {
        $img = "";

        foreach ($this->imgFile as $line)
        {
            $lineLeft = ($this->imgWidth - ($this->imgHSpaces * 2)) - strlen(utf8_decode($line));

            $imgLine = "||" . str_repeat(" ", $this->imgHSpaces) . $line . str_repeat(" ", $lineLeft) . str_repeat(" ", $this->imgHSpaces) . "||" . "\n";
            $imgLine = $this->wrapDOMTagInLine($imgLine, "span", " class='scene-img'", 2);

            $img .= $imgLine;
        }

        return $img;
    }

    private function createText() : string
    {
        $str = $this->createLine($this->imgWidth, "-", "|*", "*|");

        $lines = explode("\0", wordwrap($this->text, $this->imgWidth - 2, "\0"));

        foreach ($lines as $line)
        {
            $textLine = "|| " 
                    . $line 
                    . str_repeat(" ", ($this->imgWidth - strlen(utf8_decode($line))) - 1) 
                    . "||\n";

            $textLine = $this->wrapDOMTagInLine($textLine, "span", " class='scene-text'", 2);

            $str .= $textLine;
        }

        $str .= $this->createLine($this->imgWidth, "~", "~~", "~~");

        return $str;
    }

    private function createOptions() : string
    {
        $str = "";

        if ($this->type == "end_view")
        {
            $backLine = $this->createLine($this->imgWidth, " ", "|*", "*|");
            $backLine = $this->createMiddleTextLine($backLine, "Go back to the beginning");
            $backLine = $this->wrapDOMTagInLine($backLine, "span", " class='scene-option' data-id='start'", 2);

            $str .= $this->createLine($this->imgWidth, " ", "|*", "*|")
                  . $this->createMiddleTextLine($this->createLine($this->imgWidth, " ", "|*", "*|"), "------------------------")
                  . $backLine
                  . $this->createMiddleTextLine($this->createLine($this->imgWidth, " ", "|*", "*|"), "------------------------")
                  . $this->createLine($this->imgWidth, " ", "|*", "*|");
        }
        else
        {
            foreach ($this->options as $optIndex => $opt) {
                $optWithIndex = ($optIndex + 1) . ") " . $opt->getText();

                $lines = explode("\0", wordwrap($optWithIndex, $this->imgWidth, "\0"));

                foreach ($lines as $line)
                {
                    $optLine = "|| " 
                            . $line 
                            . str_repeat(" ", ($this->imgWidth - strlen(utf8_decode($line))) - 1)
                            . "||\n";
                
                    $optLine = $this->wrapDOMTagInLine($optLine, "span", " class='scene-option' data-id='" . $optIndex . "'", 2);

                    $str .= $optLine;
                }
            }
        }

        $str .= $this->createLine($this->imgWidth, "-", "**", "**"); 

        return $str;
    }

    private function getSceneColor(string $color) : string
    {
        return $this->sceneColors ? ($this->sceneColors[$color] ?? $this->defaultColors[$color]) : $this->defaultColors[$color];
    }

    public function getColors() : array
    {
        return [
            "color" => $this->getSceneColor("color"),
            "background" => $this->getSceneColor("background"),
            "title_color" => $this->getSceneColor("title_color"),
            "title_background" => $this->getSceneColor("title_background"),
            "image_color" => $this->getSceneColor("image_color"),
            "image_background" => $this->getSceneColor("image_background"),
            "text_color" => $this->getSceneColor("text_color"),
            "text_background" => $this->getSceneColor("text_background"),
            "option_color" => $this->getSceneColor("option_color"),
            "option_background" => $this->getSceneColor("option_background"),
            "option_hover_color" => $this->getSceneColor("option_hover_color"),
            "option_hover_background" => $this->getSceneColor("option_hover_background")
        ];
    }

    public function getStr() : string
    {
        return $this->str;
    }

    public function getId()
    {
            return $this->id;
    }

    public function getOptions() : array
    {
        return $this->options;
    }

    public function getType() : string
    {
        return $this->type;
    } 
}
?>