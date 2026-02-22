<?php

declare(strict_types=1);

namespace chalk\formatter;

use chalk\LogLevel;
use chalk\LogMessage;

abstract class ContextInterpolator implements FormatterInterface{
    protected bool $showRemainingContext;

    private const int JSON_FLAGS = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

    public function __construct(bool $showRemainingContext = false){
        $this->showRemainingContext = $showRemainingContext;
    }

    /**
     * Финальный метод format, реализующий общую логику.
     * Не переопределяйте этот метод в наследниках.
     */
    final public function format(LogLevel $level, LogMessage $message, array $extra = []): string{
        $finalMessage = $this->buildFinalMessage($message);
        return $this->doFormat($level, $finalMessage, $extra);
    }

    /**
     * Формирует итоговое сообщение с интерполяцией и неиспользованными ключами.
     */
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

    /**
     * Возвращает неиспользованные ключи контекста.
     */
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
     * Метод, который должны реализовать конкретные форматтеры.
     *
     * @param LogLevel $level       Уровень логирования
     * @param string   $finalMessage Готовое сообщение (интерполированное + неиспользованные ключи)
     * @param array    $extra        Дополнительные данные (дата, имя логгера и т.п.)
     * @return string
     */
    abstract protected function doFormat(LogLevel $level, string $finalMessage, array $extra): string;
}
