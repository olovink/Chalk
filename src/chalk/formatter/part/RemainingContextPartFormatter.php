<?php

declare(strict_types=1);

namespace chalk\formatter\part;

use chalk\LogLevel;
use chalk\LogMessage;
use chalk\style\JsonStyle;

readonly class RemainingContextPartFormatter implements PartFormatterInterface{

    public function __construct(
        private JsonStyle $jsonStyle,
        private bool $showRemaining = false
    ) {}

    public function format(LogLevel $level, LogMessage $message, array $extra): string{
        if (!$this->showRemaining) {
            return '';
        }
        $remaining = $this->getUnusedContext($message);
        if (empty($remaining)) {
            return '';
        }
        return ' ' . $this->jsonStyle->formatJson($remaining);
    }

    private function getUnusedContext(LogMessage $message): array{
        $unused = [];
        $originalMessage = $message->getMessage();
        foreach ($message->getContext() as $key => $value) {
            if (!str_contains($originalMessage, "{{$key}}")) {
                $unused[$key] = $value;
            }
        }
        return $unused;
    }
}
