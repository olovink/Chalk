<?php

declare(strict_types=1);

namespace chalk\formatter\part;

use chalk\LogLevel;
use chalk\LogMessage;
use chalk\style\Style;

readonly class LevelPartFormatter implements PartFormatterInterface{

    /**
     * @param array<string, Style> $levelStyles
     */
    public function __construct(
        private string $openBracket = '[',
        private string $closeBracket = ']',
        private ?Style $bracketStyle = null,
        private array $levelStyles = []
    ) {}

    public function format(LogMessage $message): string{
        $level = $message->getLevel();
        $levelName = strtoupper($level->name);

        $style = $this->levelStyles[$level->value] ?? null;

        $open = $this->bracketStyle ? $this->bracketStyle->apply($this->openBracket) : $this->openBracket;
        $close = $this->bracketStyle ? $this->bracketStyle->apply($this->closeBracket) : $this->closeBracket;
        $levelStr = $style ? $style->apply($levelName) : $levelName;

        return $open . $levelStr . $close;
    }
}