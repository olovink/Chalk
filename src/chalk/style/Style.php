<?php

declare(strict_types=1);

namespace chalk\style;

class Style implements StyleInterface {
    private const array ATTRIBUTE_MAP = [
        'bold' => '1',
        'underline' => '4',
        'italic' => '3',
    ];

    private const string CODE_FORMAT = "\033[%sm";

    private string $code;

    public function __construct(
        ColorInterface $color,
        bool           $bold = false,
        bool           $underline = false,
        bool           $italic = false,
        bool           $background = false
    ) {
        $codes = $this->buildAttributeCodes($bold, $underline, $italic);
        $colorCode = $color->toAnsiCode($background);
        $colorNumbers = $this->extractNumericCodes($colorCode);
        $allCodes = array_merge($codes, $colorNumbers);

        $this->code = sprintf(self::CODE_FORMAT, implode(';', $allCodes));
    }

    private function buildAttributeCodes(bool $bold, bool $underline, bool $italic): array{
        $codes = [];
        if ($bold) $codes[] = self::ATTRIBUTE_MAP['bold'];
        if ($underline) $codes[] = self::ATTRIBUTE_MAP['underline'];
        if ($italic) $codes[] = self::ATTRIBUTE_MAP['italic'];
        return $codes;
    }

    private function extractNumericCodes(string $ansiCode): array{
        if (preg_match('/\033\[([0-9;]+)m/', $ansiCode, $matches)) {
            return explode(';', $matches[1]);
        }
        return [];
    }

    public function apply(string $text): string{
        return $this->code . $text . ConsoleColor::RESET->value;
    }

    public function getCode(): string{
        return $this->code;
    }
}