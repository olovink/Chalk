<?php

declare(strict_types=1);

namespace chalk\formatter;

use chalk\LogLevel;
use chalk\LogMessage;
use chalk\style\ConsoleColor;

class PrettyConsoleFormatter extends ContextInterpolator {
    private array $colorMap;

    public function __construct(bool $showRemainingContext = false) {
        parent::__construct($showRemainingContext);

        $this->colorMap = [
            LogLevel::EMERGENCY->value => ConsoleColor::combine([ConsoleColor::BG_RED, ConsoleColor::WHITE, ConsoleColor::BOLD]),
            LogLevel::ALERT->value => ConsoleColor::combine([ConsoleColor::BG_RED, ConsoleColor::WHITE]),
            LogLevel::CRITICAL->value => ConsoleColor::combine([ConsoleColor::RED, ConsoleColor::BOLD]),
            LogLevel::ERROR->value => ConsoleColor::RED->value,
            LogLevel::WARNING->value => ConsoleColor::YELLOW->value,
            LogLevel::NOTICE->value => ConsoleColor::CYAN->value,
            LogLevel::INFO->value => ConsoleColor::GREEN->value,
            LogLevel::DEBUG->value => ConsoleColor::LIGHT_BLACK->value,
        ];
    }

    protected function doFormat(LogMessage $message, string $finalMessage): string {
        $date = $message->getDateTime()->jsonSerialize();
        $level = $message->getLevel();

        $levelName = strtoupper($level->name);
        $colorCode = $this->colorMap[$level->value] ?? ConsoleColor::WHITE->value;

        return $colorCode . "[$date] [$levelName] $finalMessage" . ConsoleColor::RESET->value;
    }
}
