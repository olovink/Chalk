<?php

declare(strict_types=1);

namespace chalk\style;

class StyleFactory{

    private const string COLOR_FORMAT = "%s|%d%d%d%d";

    /** @var array<string, StyleInterface> */
    private array $cache = [];

    public function create(
        ColorInterface $color,
        bool           $bold = false,
        bool           $underline = false,
        bool           $italic = false,
        bool           $background = false
    ): StyleInterface{
        $key = $this->buildKey($color, $bold, $underline, $italic, $background);
        if (!isset($this->cache[$key])) {
            $this->cache[$key] = new Style($color, $bold, $underline, $italic, $background);
        }
        return $this->cache[$key];
    }

    private function buildKey(
        ColorInterface $color,
        bool           $bold,
        bool           $underline,
        bool           $italic,
        bool           $background
    ): string{
        $colorKey = $this->colorToKey($color);
        return sprintf(self::COLOR_FORMAT, $colorKey, $bold, $underline, $italic, $background);
    }

    private function colorToKey(ColorInterface $color): string{
        return match (true) {
            $color instanceof RgbColor => "rgb($color->r,$color->g,$color->b)",
            $color instanceof ConsoleColor => $color->name,
            default => spl_object_hash($color)
        };
    }
}
