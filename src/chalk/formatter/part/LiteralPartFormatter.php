<?php

declare(strict_types=1);

namespace chalk\formatter\part;

use chalk\LogLevel;
use chalk\LogMessage;
use chalk\style\Style;

readonly class LiteralPartFormatter implements PartFormatterInterface{
    public function __construct(
        private string $text,
        private ?Style $style = null
    ) {}

    public function format(LogLevel $level, LogMessage $message, array $extra): string{
        return $this->style ? $this->style->apply($this->text) : $this->text;
    }
}