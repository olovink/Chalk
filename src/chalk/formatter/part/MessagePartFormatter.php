<?php

declare(strict_types=1);

namespace chalk\formatter\part;

use chalk\LogMessage;
use chalk\style\Style;

readonly class MessagePartFormatter implements PartFormatterInterface {
    public function __construct(
        private ?Style $style = null
    ) {}

    public function format(LogMessage $message): string{
        $interpolated = $message->interpolate();
        return $this->style ? $this->style->apply($interpolated) : $interpolated;
    }
}