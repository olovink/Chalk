<?php

declare(strict_types=1);

namespace chalk\style\gradient;

use chalk\style\ColorInterface;
use chalk\style\RgbColor;
use chalk\style\RgbTo256Converter;
use chalk\style\StyleFactory;
use chalk\style\StyleInterface;

class GradientApplier {
    private RgbTo256Converter $converter;
    private StyleFactory $styleFactory;

    public function __construct(
        private readonly RgbColor $start,
        private readonly RgbColor $end,
        private readonly bool     $perCharacter = true,
        private readonly bool     $bold = false,
        private readonly bool     $underline = false,
        private readonly bool     $italic = false,
        private readonly bool     $useTrueColor = false,
        ?RgbTo256Converter        $converter = null,
        ?StyleFactory             $styleFactory = null
    ) {
        $this->converter = $converter ?? new RgbTo256Converter();
        $this->styleFactory = $styleFactory ?? new StyleFactory();
    }

    public function apply(string $text): string{
        if ($text === "") {
            return "";
        }

        if ($this->perCharacter) {
            return $this->applyPerCharacter($text);
        }
        return $this->applyPerWord($text);
    }

    private function applyPerCharacter(string $text): string{
        $chars = mb_str_split($text);
        $count = count($chars);
        if ($count === 0) return "";

        $result = "";
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
        if ($count === 0) return "";

        $result = "";
        for ($i = 0; $i < $count; $i++) {
            $part = $parts[$i];
            if ($part === "") continue;
            $t = $i / ($count - 1);
            $color = $this->interpolateColor($t);
            $style = $this->createStyle($color);
            $result .= $style->apply($part);
        }
        return $result;
    }

    private function interpolateColor(float $t): RgbColor{
        $r = (int)round($this->start->r + ($this->end->r - $this->start->r) * $t);
        $g = (int)round($this->start->g + ($this->end->g - $this->start->g) * $t);
        $b = (int)round($this->start->b + ($this->end->b - $this->start->b) * $t);
        return new RgbColor($r, $g, $b);
    }

    private function createStyle(RgbColor $color): StyleInterface{
        if ($this->useTrueColor) {
            return $this->styleFactory->create($color, $this->bold, $this->underline, $this->italic);
        }
        $code256 = $this->converter->convert($color->r, $color->g, $color->b);
        $xtermColor = new readonly class($code256) implements ColorInterface {
            public function __construct(private int $code) {}

            public function toAnsiCode(bool $background = false): string{
                $prefix = $background ? '48' : '38';
                return "\033[$prefix;5;{$this->code}m";
            }
        };
        return $this->styleFactory->create($xtermColor, $this->bold, $this->underline, $this->italic);
    }
}