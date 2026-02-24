<?php

declare(strict_types=1);

namespace chalk\formatter;

use chalk\LogMessage;

class PMFormatter extends ContextInterpolator{
	protected function doFormat(LogMessage $message, string $finalMessage): string{
		return strtoupper($finalMessage);
	}
}
