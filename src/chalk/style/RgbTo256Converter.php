<?php

declare(strict_types=1);

namespace chalk\style;

class RgbTo256Converter{
    /** @var array<int, array<int, array<int, int>>> */
    private static array $cache = [];

    public function convert(int $r, int $g, int $b): int{
        if (!isset(self::$cache[$r][$g][$b])) {
            $r = (int)round($r / 51);
            $g = (int)round($g / 51);
            $b = (int)round($b / 51);
            self::$cache[$r][$g][$b] = 16 + ($r * 36) + ($g * 6) + $b;
        }
        return self::$cache[$r][$g][$b];
    }
}
