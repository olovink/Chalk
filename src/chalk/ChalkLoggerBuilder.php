<?php

declare(strict_types=1);

namespace chalk;

use chalk\handler\HandlerInterface;

class ChalkLoggerBuilder{
    private LogLevel $minLevel = LogLevel::DEBUG;
    /** @var HandlerInterface[] */
    private array $handlers = [];

    public function setMinLevel(LogLevel $level): self{
        $this->minLevel = $level;
        return $this;
    }

    public function addHandler(HandlerInterface $handler): self{
        $this->handlers[] = $handler;
        return $this;
    }

    public function build(): ChalkLogger{
        $logger = new ChalkLogger($this->minLevel);

        foreach ($this->handlers as $handler) {
            $logger->addHandler($handler);
        }
        return $logger;
    }
}