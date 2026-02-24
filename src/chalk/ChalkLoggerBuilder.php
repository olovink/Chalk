<?php

declare(strict_types=1);

namespace chalk;

use chalk\handler\HandlerInterface;

class ChalkLoggerBuilder{
    /** @var HandlerInterface[] */
    private array $handlers = [];
    private string $name = "ChalkLogger";
    private \DateTimeZone $timeZone;
    private bool $microsecondTimestamps = true;

    public function setLoggerName(string $loggerName): self{
        $this->name = $loggerName;
        return $this;
    }

    public function addHandler(HandlerInterface $handler): self{
        $this->handlers[] = $handler;
        return $this;
    }

    public function addHandlers(array $handlers): self{
        $this->handlers = array_merge($this->handlers, $handlers);
        return $this;
    }

    public function useMicrosecondTimestamps(bool $value): self{
        $this->microsecondTimestamps = $value;
        return $this;
    }

    public function setDateFormat(string $format): self{
        $this->timeZone = new \DateTimeZone($format);
        return $this;
    }

    public function build(): ChalkLogger{
        $logger = new ChalkLogger(
            $this->name,
            $this->handlers,
            $this->timeZone
        );

        $logger->useMicrosecondTimestamps($this->microsecondTimestamps);
        foreach ($this->handlers as $handler) {
            $logger->addHandler($handler);
        }
        return $logger;
    }
}