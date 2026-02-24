<?php

declare(strict_types=1);

namespace chalk\formatter\part;

use chalk\LogMessage;
use chalk\style\gradient\GradientApplier;
use chalk\style\RgbColor;

readonly class GradientMessagePartFormatter implements PartFormatterInterface {
    private GradientApplier $applier;

    public function __construct(
        RgbColor $start,
        RgbColor $end,
        bool     $perCharacter = true,
        bool     $bold = false,
        bool     $underline = false,
        bool     $italic = false,
        bool     $useTrueColor = false
    ) {
        $this->applier = new GradientApplier($start, $end, $perCharacter, $bold, $underline, $italic, $useTrueColor);
    }

    public function format(LogMessage $message): string{
        $text = $message->interpolate();
        return $this->applier->apply($text);
    }
}