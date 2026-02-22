<?php

declare(strict_types=1);

namespace chalk\style\gradient;

use chalk\style\Style;

class GradientHelper{
    /**
     * Создаёт массив стилей для градиента от start до end.
     *
     * @param RgbColor $start Начальный цвет
     * @param RgbColor $end Конечный цвет
     * @param int $steps Количество шагов (минимум 2)
     * @return Style[]
     */
    public static function createGradient(RgbColor $start, RgbColor $end, int $steps): array{
        if ($steps < 2) {
            throw new \InvalidArgumentException('Steps must be at least 2');
        }

        $styles = [];
        for ($i = 0; $i < $steps; $i++) {
            $factor = $i / ($steps - 1);
            $r = (int) round($start->r + ($end->r - $start->r) * $factor);
            $g = (int) round($start->g + ($end->g - $start->g) * $factor);
            $b = (int) round($start->b + ($end->b - $start->b) * $factor);
            $styles[] = new Style(new RgbColor($r, $g, $b));
        }
        return $styles;
    }
}