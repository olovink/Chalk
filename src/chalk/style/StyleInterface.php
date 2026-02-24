<?php

declare(strict_types=1);

namespace chalk\style;

interface StyleInterface{
    public function apply(string $text): string;

    public function getCode(): string;
}
