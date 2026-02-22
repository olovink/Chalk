<?php

declare(strict_types=1);

namespace chalk\formatter;

use chalk\LogLevel;
use chalk\LogMessage;

interface FormatterInterface{
    /**
     * Преобразует сообщение лога в строку для вывода.
     *
     * @param LogLevel   $level    Уровень логирования
     * @param LogMessage $message  Объект сообщения (текст + контекст)
     * @param array      $extra    Дополнительная информация (дата, имя логгера и т.п.)
     * @return string
     */
    public function format(LogLevel $level, LogMessage $message, array $extra = []): string;
}