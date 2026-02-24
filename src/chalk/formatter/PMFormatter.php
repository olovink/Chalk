<?php

declare(strict_types=1);

namespace chalk\formatter;

use chalk\LogMessage;
use pocketmine\thread\Thread;
use pocketmine\thread\Worker;
use pmmp\thread\Thread as NativeThread;

class PMFormatter extends ContextInterpolator{
	protected function doFormat(LogMessage $message, string $finalMessage): string{
		$timestamp = $message->getDateTime();
		$timeFormatted = $timestamp->format('H:i:s.v');

		$thread = NativeThread::getCurrentThread();
		if ($thread === null) {
			$threadName = "Server thread";
		} elseif ($thread instanceof Thread || $thread instanceof Worker) {
			$threadName = $thread->getThreadName() . " thread";
		} else {
			$threadName = (new \ReflectionClass($thread))->getShortName() . " thread";
		}

		$level = $message->getLevel()->name;

		return sprintf('[%s] [%s/%s]: %s', $timeFormatted, $threadName, $level, $finalMessage);
	}
}
