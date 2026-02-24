<?php

declare(strict_types=1);

namespace chalk\handler;

use chalk\formatter\FormatterInterface;
use chalk\formatter\StringFormatter;
use chalk\HandlerType;
use chalk\LogLevel;
use chalk\LogMessage;

class FileHandler extends BaseFileHandler{

    protected function write(string $message): void{
        file_put_contents($this->logFile, $message, self::FILE_PUT_FLAGS);
    }

    public function getHandlerType(): HandlerType{
        return HandlerType::FILE;
    }
}