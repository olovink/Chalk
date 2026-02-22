<?php

declare(strict_types=1);
namespace chalk\formatter;


use chalk\LogLevel;
use chalk\LogMessage;

class JsonFormatter implements FormatterInterface{
    public function format(LogLevel $level, LogMessage $message, array $extra = []): string{
        $data = [
            'level'   => $level->value,
            'message' => $message->getMessage(),
            'context' => $message->getContext(),
            'time'    => $extra['date'] ?? date('Y-m-d\TH:i:sP'),
            'logger'  => $extra['logger_name'] ?? '',
        ];
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
