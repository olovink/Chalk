<?php

declare(strict_types=1);

namespace chalk\handler;

use chalk\formatter\FormatterInterface;
use chalk\formatter\StringFormatter;
use chalk\HandlerType;
use chalk\LogLevel;
use chalk\LogMessage;

class FileHandler implements HandlerInterface{

    private const int FILE_PUT_FLAGS = FILE_APPEND | LOCK_EX;

    public function __construct(
        private readonly string $logFile,
        private ?FormatterInterface $formatter = null
    ) {
        $this->formatter = $formatter ?? new StringFormatter();

        if (!file_exists($this->logFile)) {
            if (!touch($this->logFile)) throw new \InvalidArgumentException(sprintf("Log file '%s' cannot be created.", $this->logFile));
        }
        if (!is_writable($this->logFile)) throw new \InvalidArgumentException(sprintf("Log file '%s' is not writable.", $this->logFile));

        $dir = dirname($logFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    public function getHandlerType(): HandlerType{
        return HandlerType::FILE;
    }

    public function handle(LogMessage $logMessage): void{
        $formatted = $this->formatter->format($logMessage) . PHP_EOL;
        file_put_contents($this->logFile, $formatted, self::FILE_PUT_FLAGS);
    }
}