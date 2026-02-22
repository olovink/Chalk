<?php

declare(strict_types=1);

namespace chalk\style;

use chalk\style\gradient\RgbColor;

class Style{
    private string $code;

    /**
     * @param string|ConsoleColor|RgbColor $color
     * @param bool $bold
     * @param bool $underline
     * @param bool $italic
     * @param bool $background Если true, цвет применяется как фоновый (только для RgbColor или ConsoleColor с фоновыми кодами)
     */
    public function __construct(
        string|ConsoleColor|RgbColor $color,
        bool $bold = false,
        bool $underline = false,
        bool $italic = false,
        bool $background = false
    ) {
        $codes = [];

        // Добавляем стили
        if ($bold) {
            $codes[] = '1';
        }
        if ($underline) {
            $codes[] = '4';
        }
        if ($italic) {
            $codes[] = '3';
        }

        // Получаем цветовой код
        if ($color instanceof ConsoleColor) {
            // Извлекаем числовой код из значения ConsoleColor (например, \033[31m -> 31)
            if (preg_match('/\033\[([0-9;]+)m/', $color->value, $matches)) {
                $codes[] = $matches[1];
            } else {
                $codes[] = trim($color->value, "\033[m"); // fallback
            }
        } elseif ($color instanceof RgbColor) {
            if ($background) {
                $codes[] = "48;2;{$color->r};{$color->g};{$color->b}";
            } else {
                $codes[] = "38;2;{$color->r};{$color->g};{$color->b}";
            }
        } else {
            // Строка с ANSI-кодом
            if (preg_match('/\033\[([0-9;]+)m/', $color, $matches)) {
                $codes[] = $matches[1];
            } else {
                $codes[] = $color;
            }
        }

        $this->code = "\033[" . implode(';', $codes) . 'm';
    }

    public function apply(string $text): string{
        return $this->code . $text . ConsoleColor::RESET->value;
    }

    public function getCode(): string{
        return $this->code;
    }
}