<?php

declare(strict_types=1);

namespace chalk\style;

use chalk\exception\ChalkLoggerException;

readonly class RgbColor implements ColorInterface{
    public function __construct(
        public int $r,
        public int $g,
        public int $b
    ) {
        if ($r < 0 || $r > 255 || $g < 0 || $g > 255 || $b < 0 || $b > 255) {
            throw new ChalkLoggerException('RGB values must be between 0 and 255');
        }
    }

    public function toAnsiCode(bool $background = false): string{
        $prefix = $background ? '48' : '38';
        return "\033[{$prefix};2;{$this->r};{$this->g};{$this->b}m";
    }

    public function asForeground(): string{
        return "\033[38;2;{$this->r};{$this->g};{$this->b}m";
    }

    public function asBackground(): string{
        return "\033[48;2;{$this->r};{$this->g};{$this->b}m";
    }
}