<?php

declare(strict_types=1);

namespace chalk\handler;

use chalk\HandlerType;

class FileHandler extends BaseFileHandler {

    protected function write(string $message): void{
        file_put_contents($this->logFile, $message, self::FILE_PUT_FLAGS);
    }

    public function getHandlerType(): HandlerType{
        return HandlerType::FILE;
    }
}