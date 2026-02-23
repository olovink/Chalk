<?php

declare(strict_types=1);

namespace chalk;

final readonly class LogMessage{

    /**
     * @param string $message  Текст сообщения
     * @param array  $context  Параметры контекста
     */
    public function __construct(
        private JsonSerializableDateTimeImmutable $dateTime,
        private LogLevel $level,
        private string  $channel,
        private string  $message,
        private array   $context = []
    ) {}

    public function getDateTime(): JsonSerializableDateTimeImmutable{
        return $this->dateTime;
    }

    public function getLevel(): LogLevel{
        return $this->level;
    }

    public function getChannel(): string{
        return $this->channel;
    }

    public function getMessage(): string{
        return $this->message;
    }

    public function getContext(): array{
        return $this->context;
    }

    public function toArray(): array{
        return [
            'datetime' => $this->dateTime,
            'channel' => $this->channel,
            'message' => $this->message,
            'context' => $this->context
        ];
    }

    public function interpolate(): string{
        $message = $this->message;
        foreach ($this->context as $key => $value) {
            if (is_string($value) || is_numeric($value)) {
                $message = str_replace("{{$key}}", (string)$value, $message);
            } elseif (is_array($value) || is_object($value)) {
                $message = str_replace("{{$key}}", json_encode($value), $message);
            }
        }
        return $message;
    }
}
