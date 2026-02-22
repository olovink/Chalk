<?php

declare(strict_types=1);

namespace chalk\formatter\part;

use chalk\LogLevel;
use chalk\LogMessage;

interface PartFormatterInterface{
    /**
     * Форматирует одну часть лога.
     */
    public function format(LogLevel $level, LogMessage $message, array $extra): string;
}