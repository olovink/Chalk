<?php

declare(strict_types=1);

namespace chalk\formatter;

use chalk\formatter\part\PartFormatterInterface;
use chalk\LogLevel;
use chalk\LogMessage;

class CompositeFormatter implements FormatterInterface{
    /** @var PartFormatterInterface[] */
    private array $parts;

    public function __construct(PartFormatterInterface ...$parts){
        $this->parts = $parts;
    }

    public function format(LogLevel $level, LogMessage $message, array $extra = []): string{
        $result = "";
        foreach ($this->parts as $part) {
            $result .= $part->format($level, $message, $extra);
        }
        return $result;
    }
}