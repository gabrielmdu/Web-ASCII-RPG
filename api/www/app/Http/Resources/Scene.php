<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Scene
 * 
 * @property int $image_width
 */
class Scene extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->mergeGameData();

        $this->image_width = $this->getImageWidth();

        return [
            'resource_type' => 'scene',
            'type' => $this->type,
            'title' => $this->title,
            'image_lines' => $this->getImageLines(),
            'image_interval' => $this->image_interval,
            'image_width' => $this->image_width,
            'text_lines' => $this->getTextLines(),
            'options' => $this->getOptionsWithLines(),
            'colors' => $this->colors,
            'chars' => $this->chars,
            'in_anim' => $this->in_anim,
            'out_anim' => $this->out_anim,
            'border_width' => $this->border_width
        ];
    }

    private function mergeGameData()
    {
        $gameInfo = $this->game->adventure;

        $this->colors = array_merge($gameInfo['colors'], $this->colors);
        $this->chars = $gameInfo['chars'];
        $this->border_width = $gameInfo['border_width'];
        $this->in_anim = $this->in_anim ?? $gameInfo['animations']['in'];
        $this->out_anim = $this->out_anim ?? $gameInfo['animations']['out'];
    }

    private function getImageLines(): array
    {
        $allLines = [];
        foreach ($this->image as $img) {
            $lines = explode("\n", $img);
            $allLines[] = $this->mapLinesSizes($lines);
        }

        return $allLines;
    }

    private function getImageWidth(): int
    {
        $img = $this->image[0];
        $lines = explode("\n", $img);
        usort($lines, fn ($a, $b) => mb_strlen($a) <=> mb_strlen($b));
        return mb_strlen(array_pop($lines)) + ($this->border_width * 2);
    }

    private function getTextLines(): array
    {
        $lines = explode("\0", wordwrap($this->text, $this->image_width - ($this->border_width * 2), "\0"));
        return $this->mapLinesSizes($lines);
    }

    private function getOptionsWithLines(): array
    {
        if (!$this->options) {
            return [];
        }

        $optLines = [];

        foreach ($this->options as $optIndex => $opt) {
            $optWithIndex = ($optIndex + 1) . ') ' . $opt['text'];
            $lines = explode("\0", wordwrap($optWithIndex, $this->image_width - ($this->border_width * 2), "\0"));

            $optLines[] = [
                'lines' => $this->mapLinesSizes($lines),
                'out_anim' => $opt['out_anim'] ?? null
            ];
        }

        return $optLines;
    }

    private function mapLinesSizes(array $lines): array
    {
        return array_map(fn ($line) => [
            'str' => $line,
            'size' => mb_strlen($line)
        ], $lines);
    }
}
