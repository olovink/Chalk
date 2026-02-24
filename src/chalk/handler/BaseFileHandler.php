<?php

declare(strict_types=1);

namespace chalk\handler;

use chalk\exception\ChalkLoggerException;
use chalk\formatter\FormatterInterface;
use chalk\formatter\StringFormatter;
use chalk\LogMessage;

abstract class BaseFileHandler implements HandlerInterface {
    public const int FILE_PUT_FLAGS = FILE_APPEND | LOCK_EX;

    /**
     * @throws ChalkLoggerException
     */
    public function __construct(
        protected readonly string   $logFile,
        private ?FormatterInterface $formatter = null
    ) {
        $this->formatter = $formatter ?? new StringFormatter();

        $dir = dirname($logFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (!file_exists($this->logFile)) {
            if (!touch($this->logFile)) throw new ChalkLoggerException(sprintf("Log file '%s' cannot be created.", $this->logFile));
        }
        if (!is_writable($this->logFile)) throw new ChalkLoggerException(sprintf("Log file '%s' is not writable.", $this->logFile));
    }

    final public function handle(LogMessage $logMessage): void{
        $formatted = $this->formatter->format($logMessage) . PHP_EOL;
        $this->write($formatted);
    }

    abstract protected function write(string $message): void;
}