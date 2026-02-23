<?php

declare(strict_types=1);

namespace chalk\formatter;

use chalk\formatter\part\PartFormatterInterface;
use chalk\LogMessage;

class CompositeFormatter implements FormatterInterface{
    /** @var PartFormatterInterface[] */
    private array $parts;

    public function __construct(PartFormatterInterface ...$parts){
        $this->parts = $parts;
    }

    public function format(LogMessage $message): string{
        return implode("", array_map(static fn($part) => $part->format($message), $this->parts));
    }
}