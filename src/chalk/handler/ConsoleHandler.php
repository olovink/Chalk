<?php

declare(strict_types=1);

namespace chalk\handler;

use chalk\formatter\FormatterInterface;
use chalk\formatter\StringFormatter;
use chalk\HandlerType;
use chalk\LogLevel;
use chalk\LogMessage;
use chalk\SimpleChalkLogger;

class ConsoleHandler implements HandlerInterface{
    private FormatterInterface $formatter;
    private \Logger $logger;

    public function __construct(
        ?\Logger $logger = null,
        ?FormatterInterface $formatter = null
    ) {
        $this->logger = $logger ?? new SimpleChalkLogger();
        $this->formatter = $formatter ?? new StringFormatter();
    }

    public function getHandlerType(): HandlerType{
        return HandlerType::CONSOLE;
    }

    public function handle(LogMessage $logMessage): void{
        $extra = [
            'logger_name' => 'Console',
        ];

        $formatted = $this->formatter->format($logMessage, $extra);
        $this->logger->log(null, $formatted);
    }
}