<?php

declare(strict_types=1);

namespace chalk\style\gradient;

use chalk\style\Style;

class GradientApplier{

    private static array $styleCache = [];
    private static array $rgbTo256Cache = [];

    public function __construct(
        private readonly RgbColor $start,
        private readonly RgbColor $end,
        private readonly bool $perCharacter = true,
        private readonly bool $bold = false,
        private readonly bool $underline = false,
        private readonly bool $italic = false,
        private readonly bool $useTrueColor = false
    ) {}

    public function apply(string $text): string{
        if ($text === '') {
            return '';
        }

        if ($this->perCharacter) {
            return $this->applyPerCharacter($text);
        } else {
            return $this->applyPerWord($text);
        }
    }

    private function applyPerCharacter(string $text): string{
        $chars = mb_str_split($text);
        $count = count($chars);
        if ($count === 0) return '';

        $result = '';
        for ($i = 0; $i < $count; $i++) {
            $t = $i / ($count - 1);
            $color = $this->interpolateColor($t);
            $style = $this->createStyle($color);
            $result .= $style->apply($chars[$i]);
        }
        return $result;
    }

    private function applyPerWord(string $text): string{
        $parts = preg_split('/(\s+|[.,!?:;"\'\(\)\[\]{}])/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        $count = count($parts);
        if ($count === 0) return '';

        $result = '';
        for ($i = 0; $i < $count; $i++) {
            $part = $parts[$i];
            if ($part === '') continue;
            $t = $i / ($count - 1);
            $color = $this->interpolateColor($t);
            $style = $this->createStyle($color);
            $result .= $style->apply($part);
        }
        return $result;
    }

    private function interpolateColor(float $t): RgbColor
    {
        $r = (int) round($this->start->r + ($this->end->r - $this->start->r) * $t);
        $g = (int) round($this->start->g + ($this->end->g - $this->start->g) * $t);
        $b = (int) round($this->start->b + ($this->end->b - $this->start->b) * $t);
        return new RgbColor($r, $g, $b);
    }

    private function createStyle(RgbColor $color): Style {
        $flags = ($this->bold ? 4 : 0) | ($this->underline ? 2 : 0) | ($this->italic ? 1 : 0); // Pack RGB + flags into a single int key
        $key = ($color->r << 24) | ($color->g << 16) | ($color->b << 8) | $flags;

        if (!isset(self::$styleCache[$key])) {
            if ($this->useTrueColor) {
                self::$styleCache[$key] = new Style($color, $this->bold, $this->underline, $this->italic);
            } else {
                if (!isset(self::$rgbTo256Cache[$color->r][$color->g][$color->b])) {
                    // Cache RGB→256 conversion
                    self::$rgbTo256Cache[$color->r][$color->g][$color->b] = $this->rgbTo256($color->r, $color->g, $color->b);
                }
                $code256 = self::$rgbTo256Cache[$color->r][$color->g][$color->b];
                $ansiCode = "\033[38;5;{$code256}m";
                self::$styleCache[$key] = new Style($ansiCode, $this->bold, $this->underline, $this->italic);
            }
        }
        return self::$styleCache[$key];
    }

    /**
     * Преобразует RGB в ближайший цвет из 256-цветной палитры.
     */
    private function rgbTo256(int $r, int $g, int $b): int{
        $r = (int) round($r / 51);
        $g = (int) round($g / 51);
        $b = (int) round($b / 51);
        return 16 + ($r * 36) + ($g * 6) + $b;
    }
}