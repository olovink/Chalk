<?php

declare(strict_types=1);

namespace chalk\handler;

use chalk\HandlerType;
use chalk\LogLevel;
use chalk\LogMessage;

interface HandlerInterface {
    public function getHandlerType(): HandlerType;

    public function handle(LogMessage $logMessage): void;
}