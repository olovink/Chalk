<?php

declare(strict_types=1);

namespace style;

use chalk\style\ColorInterface;
use chalk\style\ConsoleColor;

class CombinedColor implements ColorInterface {
	private string $code;

	public function __construct(ConsoleColor ...$colors) {
		$this->code = ConsoleColor::combine($colors);
	}

	public function getCode(): string {
		return $this->code;
	}

	public function toAnsiCode(bool $background = false): string{
		return $this->code;
	}
}
