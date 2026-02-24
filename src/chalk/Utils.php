<?php

declare(strict_types=1);

namespace chalk;

class Utils {

    public static function substr(string $string, int $start, ?int $length = null): string{
        if (\extension_loaded('mbstring')) {
            return mb_strcut($string, $start, $length);
        }

        return substr($string, $start, (null === $length) ? \strlen($string) : $length);
    }
}
