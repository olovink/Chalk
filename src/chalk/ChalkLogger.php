<?php

declare(strict_types=1);

namespace chalk;

use chalk\exception\ChalkLoggerException;
use chalk\handler\HandlerInterface;
use pocketmine\Server;

class ChalkLogger {

    /** @var array<HandlerInterface> */
    private array $handlers = [];
    private LogLevel $minLevel;

    public function __construct(?LogLevel $minLevel = null) {
        $this->minLevel = $minLevel ?? LogLevel::DEBUG;
    }

    public function addHandler(HandlerInterface $handler): void {
        $this->handlers[] = $handler;
    }

    /** @return array<HandlerInterface> */
    public function getHandlers(): array {
        return $this->handlers;
    }

    public function log(LogLevel $level, string $message, array $context = []): void {
        if ($level->getPriority() < $this->minLevel->getPriority()) {
            return;
        }
        $logMessage = new LogMessage($message, $context);
        foreach ($this->handlers as $handler) {
            try {
                $handler->handle($level, $logMessage);
            } catch (\Throwable $e) {
                Server::getInstance()->getLogger()->error(
                    "ChalkLogger: Handler error: " . $e->getMessage()
                );
            }
        }
    }

    public function debug(string $message, array $context = []): void {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    public function info(string $message, array $context = []): void {
        $this->log(LogLevel::INFO, $message, $context);
    }

    public function warning(string $message, array $context = []): void {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    public function error(string $message, array $context = []): void {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    public function logException(\Throwable $exception, array $context = []): void{
        $context['exception'] = [
            'class' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ];
        $this->error($exception->getMessage(), $context);
    }
}