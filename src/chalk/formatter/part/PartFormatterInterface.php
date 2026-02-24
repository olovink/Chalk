<?php

declare(strict_types=1);

namespace chalk\formatter\part;

use chalk\LogMessage;

interface PartFormatterInterface {
    public function format(LogMessage $message): string;
}