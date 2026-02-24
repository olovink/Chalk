<?php

declare(strict_types=1);

namespace chalk;

use chalk\handler\HandlerInterface;
use DateTimeZone;
use pocketmine\Server;

class ChalkLogger {

    /** @var array<HandlerInterface> */
    private array $handlers = [];
    private DateTimeZone $timezone;

    private bool $microsecondTimestamps = true;

    public function __construct(
        private readonly string $name,
        array                   $handlers = [],
        ?\DateTimeZone          $timezone = null,
    ) {
        $this->setHandlers($handlers);
        $this->timezone = $timezone ?? new DateTimeZone(date_default_timezone_get());
    }


    public function getName(): string{
        return $this->name;
    }

    public function addHandler(HandlerInterface $handler): void {
        $this->handlers[] = $handler;
    }

    public function setHandlers(array $handlers): void{
        foreach ($handlers as $handler) {
            $this->addHandler($handler);
        }
    }

    /** @return array<HandlerInterface> */
    public function getHandlers(): array {
        return $this->handlers;
    }

    public function log(LogLevel $level, string $message, array $context = [], ?JsonSerializableDateTimeImmutable $datetime = null): void {
        $logMessage = new LogMessage(
            dateTime: $datetime ?? new JsonSerializableDateTimeImmutable($this->microsecondTimestamps, $this->timezone),
            level: $level,
            channel: $this->name,
            message: $message,
            context: $context,
        );

        foreach ($this->handlers as $handler) {
            try {
                $handler->handle($logMessage);
            } catch (\Throwable $e) {
                Server::getInstance()->getLogger()->error(
                    "ChalkLogger: Handler error: " . $e->getMessage() . PHP_EOL .
                    "Line: " . $e->getLine() . PHP_EOL .
                    "File: " . $e->getFile() . PHP_EOL .
                    "Code: " . $e->getCode() . PHP_EOL .
                    "Trace: " . $e->getTraceAsString() . PHP_EOL
                );
            }
        }
    }

    public function useMicrosecondTimestamps(bool $value): void{
        $this->microsecondTimestamps = $value;
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