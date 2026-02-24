<?php

declare(strict_types=1);

namespace handler;

use chalk\formatter\FormatterInterface;
use chalk\handler\FileHandler;

class BufferedFileHandler extends FileHandler{

    /** @var array<string> */
    private array $buffer = [];

    /**
     * @param int $bufferSize Number of messages to accumulate before recording
     */
    public function __construct(
        string $logFile,
        private readonly int $bufferSize = 100,
        ?FormatterInterface $formatter = null,
    ) {
        parent::__construct($logFile, $formatter);

        if (1 > $this->bufferSize) {
            throw new \InvalidArgumentException("Buffer size must be a positive number");
        }
    }

    public function write(string $message): void{
        $this->buffer[] = $message;

        if (count($this->buffer) >= $this->bufferSize) {
            $this->flush();
        }
    }

    public function flush(): void{
        if (count($this->buffer) === 0) {   
            return;
        }
        $content = implode(" ", $this->buffer);

        $this->buffer = [];
        file_put_contents($this->logFile, $content, self::FILE_PUT_FLAGS);
    }

    public function __destruct() {
        $this->flush();
    }

}
