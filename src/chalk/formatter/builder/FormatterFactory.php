<?php

declare(strict_types=1);

namespace chalk\formatter\builder;

use chalk\formatter\CompositeFormatter;
use chalk\LogLevel;
use chalk\style\ConsoleColor;
use chalk\style\JsonStyle;
use chalk\style\Style;

class FormatterFactory{
    public static function createDefault(bool $showRemainingContext = false): CompositeFormatter{
        return (new FormatterBuilder())
            ->addDatePart('[', ']', null, new Style(ConsoleColor::LIGHT_BLACK))
            ->addLiteral(' ')
            ->addLevelPart('[', ']', null, [
                LogLevel::EMERGENCY->value => new Style(ConsoleColor::combine([ConsoleColor::BG_RED, ConsoleColor::WHITE, ConsoleColor::BOLD])),
                LogLevel::ALERT->value     => new Style(ConsoleColor::combine([ConsoleColor::BG_RED, ConsoleColor::WHITE])),
                LogLevel::CRITICAL->value  => new Style(ConsoleColor::combine([ConsoleColor::RED, ConsoleColor::BOLD])),
                LogLevel::ERROR->value     => new Style(ConsoleColor::RED),
                LogLevel::WARNING->value   => new Style(ConsoleColor::YELLOW),
                LogLevel::NOTICE->value    => new Style(ConsoleColor::CYAN),
                LogLevel::INFO->value      => new Style(ConsoleColor::GREEN),
                LogLevel::DEBUG->value     => new Style(ConsoleColor::LIGHT_BLACK),
            ])
            ->addLiteral(' ')
            ->addMessagePart(new Style(ConsoleColor::WHITE))
            ->addRemainingContextPart(
                new JsonStyle(
                    braceColor: ConsoleColor::YELLOW,
                    bracketColor: ConsoleColor::YELLOW,
                    keyColor: ConsoleColor::CYAN,
                    stringColor: ConsoleColor::WHITE,
                    numberColor: ConsoleColor::MAGENTA,
                    boolColor: ConsoleColor::LIGHT_BLUE,
                    nullColor: ConsoleColor::LIGHT_BLACK,
                    quoteColor: ConsoleColor::LIGHT_BLACK,
                    colonColor: ConsoleColor::LIGHT_BLACK,
                    commaColor: ConsoleColor::LIGHT_BLACK,
                ),
                $showRemainingContext
            )
            ->build();
    }

    public static function createMinimal(): CompositeFormatter{
        return (new FormatterBuilder())
            ->addDatePart()
            ->addLiteral(' ')
            ->addLevelPart()
            ->addLiteral(' ')
            ->addMessagePart()
            ->build();
    }
}
