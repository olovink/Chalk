<?php

declare(strict_types=1);

namespace chalk\formatter;

use chalk\LogMessage;

abstract class ContextInterpolator implements FormatterInterface {
    protected bool $showRemainingContext;

    private const int JSON_FLAGS = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

    public function __construct(bool $showRemainingContext = false){
        $this->showRemainingContext = $showRemainingContext;
    }

    final public function format(LogMessage $message): string{
        $finalMessage = $this->buildFinalMessage($message);
        return $this->doFormat($message, $finalMessage);
    }


    protected function buildFinalMessage(LogMessage $message): string{
        $parts = [];
        $parts[] = $message->interpolate();

        if ($this->showRemainingContext) {
            $remaining = $this->getUnusedContext($message);
            if (!count($remaining) == 0) {
                $parts[] = json_encode($remaining, self::JSON_FLAGS);
            }
        }

        return implode(" ", $parts);
    }

    protected function getUnusedContext(LogMessage $message): array{
        $unused = [];
        $originalMessage = $message->getMessage();
        foreach ($message->getContext() as $key => $value) {
            if (!str_contains($originalMessage, "{{$key}}")) {
                $unused[$key] = $value;
            }
        }
        return $unused;
    }

    /**
     * @param LogMessage $message
     * @param string $finalMessage
     * @return string
     */
    abstract protected function doFormat(LogMessage $message, string $finalMessage): string;
}