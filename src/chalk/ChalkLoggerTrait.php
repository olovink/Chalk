<?php

declare(strict_types=1);

namespace chalk;

use http\Exception\RuntimeException;

trait ChalkLoggerTrait {

	protected ?ChalkLogger $chalkLogger = null;

	public function setChalkLogger(ChalkLogger $logger): void {
		$this->chalkLogger = $logger;
	}

	public function getChalkLogger(): ChalkLogger {
		if ($this->chalkLogger === null) {
			throw new RuntimeException(sprintf("ChalkLogger not initialised in %s", static::class));
		}
		return $this->chalkLogger;
	}
}
