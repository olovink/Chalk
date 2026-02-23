<?php

declare(strict_types=1);

namespace chalk\formatter\part;

use chalk\LogLevel;
use chalk\LogMessage;
use chalk\style\Style;

readonly class DatePartFormatter implements PartFormatterInterface{
    public function __construct(
        private string $openBracket = '[',
        private string $closeBracket = ']',
        private ?Style $bracketStyle = null,
        private ?Style $dateStyle = null
    ) {}

    public function format(LogMessage $message): string{
        $date = $message->getDateTime();

        $open = $this->bracketStyle ? $this->bracketStyle->apply($this->openBracket) : $this->openBracket;
        $close = $this->bracketStyle ? $this->bracketStyle->apply($this->closeBracket) : $this->closeBracket;
        $dateStr = $this->dateStyle ? $this->dateStyle->apply($date->jsonSerialize()) : $date;

        return $open . $dateStr . $close;
    }
}
