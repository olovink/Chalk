<?php

namespace chalk\formatter;

use chalk\LogMessage;

class StringFormatter extends ContextInterpolator {
    private string $template;

    public function __construct(string $template = '[{date}] [{level}]: {message}', bool $showRemainingContext = false) {
        parent::__construct($showRemainingContext);
        $this->template = $template;
    }

    protected function doFormat(LogMessage $message, string $finalMessage): string{
        $replace = [
            '{level}' => strtoupper($message->getLevel()->name),
            '{message}' => $finalMessage,
            '{date}' => $message->getDateTime()->jsonSerialize(),
            '{logger}' => $message->getContext(),
        ];

        return str_replace(array_keys($replace), array_values($replace), $this->template);
    }
}