<?php

declare(strict_types=1);

namespace chalk\formatter\part;

use chalk\LogLevel;
use chalk\LogMessage;
use chalk\style\Style;

readonly class MessagePartFormatter implements PartFormatterInterface{
    public function __construct(
        private ?Style $style = null
    ) {}

    public function format(LogLevel $level, LogMessage $message, array $extra): string{
        $interpolated = $message->interpolate();
        return $this->style ? $this->style->apply($interpolated) : $interpolated;
    }
}