<?php

declare(strict_types=1);

namespace chalk\formatter\builder;

use chalk\formatter\CompositeFormatter;
use chalk\formatter\part\DatePartFormatter;
use chalk\formatter\part\GradientMessagePartFormatter;
use chalk\formatter\part\GradientPartDecorator;
use Chalk\formatter\part\LevelPartFormatter;
use chalk\formatter\part\LiteralPartFormatter;
use chalk\formatter\part\MessagePartFormatter;
use chalk\formatter\part\PartFormatterInterface;
use chalk\formatter\part\RemainingContextPartFormatter;
use chalk\style\JsonStyle;
use chalk\style\RgbColor;
use chalk\style\Style;

class FormatterBuilder {
    /** @var PartFormatterInterface[] */
    private array $parts = [];

    public function addDatePart(
        string $openBracket = '[',
        string $closeBracket = ']',
        ?Style $bracketStyle = null,
        ?Style $dateStyle = null
    ): self{
        $this->parts[] = new DatePartFormatter($openBracket, $closeBracket, $bracketStyle, $dateStyle);
        return $this;
    }

    public function addLevelPart(
        string $openBracket = '[',
        string $closeBracket = ']',
        ?Style $bracketStyle = null,
        array  $levelStyles = []
    ): self{
        $this->parts[] = new LevelPartFormatter($openBracket, $closeBracket, $bracketStyle, $levelStyles);
        return $this;
    }

    public function addGradientDatePart(
        RgbColor $start,
        RgbColor $end,
        string   $openBracket = '[',
        string   $closeBracket = ']',
        ?Style   $bracketStyle = null,
        bool     $perCharacter = true,
        bool     $bold = false,
        bool     $underline = false,
        bool     $italic = false,
        bool     $useTrueColor = false
    ): self{
        $inner = new DatePartFormatter($openBracket, $closeBracket, $bracketStyle, null);
        $decorated = new GradientPartDecorator($inner, $start, $end, $perCharacter, $bold, $underline, $italic, $useTrueColor);
        $this->parts[] = $decorated;
        return $this;
    }

    public function addGradientLevelPart(
        RgbColor $start,
        RgbColor $end,
        string   $openBracket = '[',
        string   $closeBracket = ']',
        ?Style   $bracketStyle = null,
        bool     $perCharacter = true,
        bool     $bold = false,
        bool     $underline = false,
        bool     $italic = false,
        bool     $useTrueColor = false
    ): self{
        $this->parts[] = new LiteralPartFormatter($openBracket, $bracketStyle);
        $levelPart = new LevelPartFormatter('', '', null, []);
        $decorated = new GradientPartDecorator($levelPart, $start, $end, $perCharacter, $bold, $underline, $italic, $useTrueColor);
        $this->parts[] = $decorated;
        $this->parts[] = new LiteralPartFormatter($closeBracket, $bracketStyle);
        return $this;
    }

    public function addGradientLiteral(
        string   $text,
        RgbColor $start,
        RgbColor $end,
        bool     $perCharacter = true,
        bool     $bold = false,
        bool     $underline = false,
        bool     $italic = false,
        bool     $useTrueColor = false
    ): self{
        $inner = new LiteralPartFormatter($text);
        $decorated = new GradientPartDecorator($inner, $start, $end, $perCharacter, $bold, $underline, $italic, $useTrueColor);
        $this->parts[] = $decorated;
        return $this;
    }

    public function addGradientMessagePart(RgbColor $start, RgbColor $end, bool $perCharacter = true): self{
        $this->parts[] = new GradientMessagePartFormatter($start, $end, $perCharacter);
        return $this;
    }

    public function addMessagePart(?Style $style = null): self{
        $this->parts[] = new MessagePartFormatter($style);
        return $this;
    }

    public function addRemainingContextPart(JsonStyle $jsonStyle, bool $showRemaining = false): self{
        $this->parts[] = new RemainingContextPartFormatter($jsonStyle, $showRemaining);
        return $this;
    }

    public function addLiteral(string $text, ?Style $style = null): self{
        $this->parts[] = new LiteralPartFormatter($text, $style);
        return $this;
    }

    public function build(): CompositeFormatter{
        return new CompositeFormatter(...$this->parts);
    }
}