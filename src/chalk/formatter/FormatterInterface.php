<?php

declare(strict_types=1);

namespace chalk\formatter;

use chalk\LogLevel;
use chalk\LogMessage;

interface FormatterInterface{
    /**
     * Преобразует сообщение лога в строку для вывода.
     *
     * @param LogMessage $message  Объект сообщения (текст + контекст)
     * @return string
     */
    public function format(LogMessage $message): string;
}