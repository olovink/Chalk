<?php

declare(strict_types=1);

namespace chalk;

class SimpleChalkLogger implements \Logger{
    public function emergency($message): void{
        $this->log(LogLevel::EMERGENCY, $message);
    }

    public function alert($message): void{
        $this->log(LogLevel::ALERT, $message);
    }

    public function critical($message): void{
        $this->log(LogLevel::CRITICAL, $message);
    }

    public function error($message): void{
        $this->log(LogLevel::ERROR, $message);
    }

    public function warning($message): void{
        $this->log(LogLevel::WARNING, $message);
    }

    public function notice($message): void{
        $this->log(LogLevel::NOTICE, $message);
    }

    public function info($message): void{
        $this->log(LogLevel::INFO, $message);
    }

    public function debug($message): void{
        $this->log(LogLevel::DEBUG, $message);
    }

    public function log($level, $message): void{
        echo $message . PHP_EOL;
    }

    public function logException(\Throwable $e, $trace = null): void{
        $this->critical($e->getMessage());
        echo $e->getTraceAsString();
    }
}
