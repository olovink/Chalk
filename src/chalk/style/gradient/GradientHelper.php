<?php

declare(strict_types=1);

namespace chalk\style\gradient;

use chalk\exception\ChalkLoggerException;
use chalk\style\ColorInterface;
use chalk\style\RgbColor;
use chalk\style\RgbTo256Converter;
use chalk\style\StyleFactory;

class GradientHelper
{
    public static function createGradient(
        RgbColor      $start,
        RgbColor      $end,
        int           $steps,
        bool          $useTrueColor = false,
        ?StyleFactory $factory = null
    ): array{
        if ($steps < 2) {
            throw new ChalkLoggerException('Steps must be at least 2');
        }

        $factory = $factory ?? new StyleFactory();
        $converter = new RgbTo256Converter();
        $styles = [];

        for ($i = 0; $i < $steps; $i++) {
            $factor = $i / ($steps - 1);
            $r = (int)round($start->r + ($end->r - $start->r) * $factor);
            $g = (int)round($start->g + ($end->g - $start->g) * $factor);
            $b = (int)round($start->b + ($end->b - $start->b) * $factor);
            $color = new RgbColor($r, $g, $b);

            if ($useTrueColor) {
                $styles[] = $factory->create($color);
            } else {
                $code256 = $converter->convert($r, $g, $b);
                $xtermColor = new readonly class($code256) implements ColorInterface {
                    public function __construct(private int $code)
                    {
                    }

                    public function toAnsiCode(bool $background = false): string
                    {
                        $prefix = $background ? '48' : '38';
                        return "\033[$prefix;5;{$this->code}m";
                    }
                };
                $styles[] = $factory->create($xtermColor);
            }
        }
        return $styles;
    }
}