<?php 
class Option
{
    private $destiny;
    private $text;

    public function __construct(string $destiny, string $text)
    {
        $this->destiny = $destiny;
        $this->text = $text;
    }

    public function getDestiny() : string
    {
        return $this->destiny;
    }

    public function gettext() : string
    {
        return $this->text;
    }
}
?>