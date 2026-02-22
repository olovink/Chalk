<?php

namespace chalk\formatter;

use chalk\LogLevel;

class StringFormatter extends ContextInterpolator{
    private string $template;

    public function __construct(string $template = '[{date}] [{level}]: {message}', bool $showRemainingContext = false){
        parent::__construct($showRemainingContext);
        $this->template = $template;
    }

    protected function doFormat(LogLevel $level, string $finalMessage, array $extra): string{
        $replace = [
            '{level}'   => strtoupper($level->name),
            '{message}' => $finalMessage,
            '{date}'    => $extra['date'] ?? date('Y-m-d H:i:s'),
            '{logger}'  => $extra['logger_name'] ?? '',
        ];

        return str_replace(array_keys($replace), array_values($replace), $this->template);
    }
}