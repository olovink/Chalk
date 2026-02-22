<?php

declare(strict_types=1);

namespace chalk;

enum HandlerType: string{
    case FILE = 'file';
    case CONSOLE = 'console';
}
