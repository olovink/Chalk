<?php

declare(strict_types=1);

namespace chalk\formatter;

use chalk\LogMessage;

class JsonFormatter implements FormatterInterface {
    public function format(LogMessage $message): string{
        $data = [
            'level' => $message->getLevel()->name,
            'message' => $message->getMessage(),
            'context' => $message->getContext(),
            'time' => $message->getDateTime()->jsonSerialize(),
            'logger' => $message->getChannel(),
        ];
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
