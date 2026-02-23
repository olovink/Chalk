<?php

declare(strict_types=1);

namespace chalk\handler;

use chalk\formatter\FormatterInterface;
use chalk\formatter\StringFormatter;
use chalk\HandlerType;
use chalk\LogLevel;
use chalk\LogMessage;

class FileHandler implements HandlerInterface{
    private string $logFile;
    private FormatterInterface $formatter;

    public function __construct(string $logFile, ?FormatterInterface $formatter = null){
        $this->logFile = $logFile;
        $this->formatter = $formatter ?? new StringFormatter();

        $dir = dirname($logFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    public function getHandlerType(): HandlerType{
        return HandlerType::FILE;
    }

    public function handle(LogMessage $logMessage): void{
        $extra = [
            'logger_name' => 'File',
        ];

        $formatted = $this->formatter->format($logMessage, $extra) . PHP_EOL;
        file_put_contents($this->logFile, $formatted, FILE_APPEND | LOCK_EX);
    }
}