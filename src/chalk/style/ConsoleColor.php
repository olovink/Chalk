<?php

declare(strict_types=1);

namespace chalk\style;

enum ConsoleColor: string implements ColorInterface{
    // Сброс форматирования
    case RESET = "\033[0m";

    // Обычные цвета текста (foreground)
    case BLACK = "\033[0;30m";
    case RED = "\033[0;31m";
    case GREEN = "\033[0;32m";
    case YELLOW = "\033[0;33m";
    case BLUE = "\033[0;34m";
    case MAGENTA = "\033[0;35m";
    case CYAN = "\033[0;36m";
    case WHITE = "\033[0;37m";

    // Яркие (light) цвета текста
    case LIGHT_BLACK = "\033[1;30m";   // Dark Gray
    case LIGHT_RED = "\033[1;31m";
    case LIGHT_GREEN = "\033[1;32m";
    case LIGHT_YELLOW = "\033[1;33m";
    case LIGHT_BLUE = "\033[1;34m";
    case LIGHT_MAGENTA = "\033[1;35m";
    case LIGHT_CYAN = "\033[1;36m";
    case LIGHT_WHITE = "\033[1;37m";   // Bright White

    // Фоновые цвета (background)
    case BG_BLACK = "\033[40m";
    case BG_RED = "\033[41m";
    case BG_GREEN = "\033[42m";
    case BG_YELLOW = "\033[43m";
    case BG_BLUE = "\033[44m";
    case BG_MAGENTA = "\033[45m";
    case BG_CYAN = "\033[46m";
    case BG_WHITE = "\033[47m";

    // Яркие фоновые цвета
    case BG_LIGHT_BLACK = "\033[100m";
    case BG_LIGHT_RED = "\033[101m";
    case BG_LIGHT_GREEN = "\033[102m";
    case BG_LIGHT_YELLOW = "\033[103m";
    case BG_LIGHT_BLUE = "\033[104m";
    case BG_LIGHT_MAGENTA = "\033[105m";
    case BG_LIGHT_CYAN = "\033[106m";
    case BG_LIGHT_WHITE = "\033[107m";

    // Стили
    case BOLD = "\033[1m";
    case DIM = "\033[2m";
    case ITALIC = "\033[3m";
    case UNDERLINE = "\033[4m";
    case BLINK = "\033[5m";
    case REVERSE = "\033[7m";
    case HIDDEN = "\033[8m";
    case STRIKETHROUGH = "\033[9m";

    /**
     * Возвращает ANSI-код цвета.
     */
    public function getCode(): string{
        return $this->value;
    }

    /**
     * Применить цвет к тексту (оборачивает текст в цвет и сбрасывает в конце).
     */
    public function apply(string $text): string{
        return $this->value . $text . self::RESET->value;
    }

    /**
     * Комбинирует несколько цветов/стилей (например, красный + жирный).
     *
     * @param self[] $colors Массив цветов/стилей
     * @return string Комбинированная escape-последовательность
     */
    public static function combine(array $colors): string{
        $codes = [];
        foreach ($colors as $color) {
            if (preg_match('/\033\[([0-9;]+)m/', $color->value, $matches)) {
                $codes[] = $matches[1];
            }
        }
        if (count($codes) == 0) {
            return "";
        }
        return "\033[" . implode(';', $codes) . 'm';
    }

    public function toAnsiCode(bool $background = false): string{
        if ($background) {
            // TODO: BG_COLOR
            return $this->value;
        }
        return $this->value;
    }
}
