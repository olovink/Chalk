<?php

declare(strict_types=1);

namespace chalk\style;

interface ColorInterface{
    public function toAnsiCode(bool $background = false): string;
}
