<?php

declare(strict_types=1);

namespace chalk\formatter;

use chalk\LogLevel;
use chalk\LogMessage;

class CallableFormatter implements FormatterInterface{
    private \Closure $closure;

    /**
     * @param callable $callback Сигнатура: function(LogLevel $level, LogMessage $message, array $extra): string
     */
    public function __construct(callable $callback){
        $this->closure = $callback instanceof \Closure ? $callback : $callback(...);
    }

    public function format(LogLevel $level, LogMessage $message, array $extra = []): string{
        return ($this->closure)($level, $message, $extra);
    }
}