# Chalk Logger

**Chalk** – это гибкая и расширяемая библиотека для логирования в PHP, разработанная с использованием современных паттернов проектирования. Она позволяет создавать красочные, структурированные логи с поддержкой ANSI-цветов, градиентов, а также легко настраивать вывод для разных окружений (консоль, файлы, и т.д.). Особое внимание уделено интеграции с **PocketMine-MP**, но библиотека может использоваться в любом PHP-проекте.

## Особенности

- ✅ **Полный контроль над цветами** – использование ANSI 16/256 цветов и TrueColor (RGB).
- ✅ **Градиенты** для любой части лога (дата, уровень, сообщение).
- ✅ **Интерполяция контекста** (подстановка значений из массива в сообщение).
- ✅ **Готов к использованию в PocketMine-MP** – использует родной логгер PMMP.

## Требования

- PHP 8.3 или выше

## Примеры использования

### 1. Базовое логирование (PocketMine-MP)

```php
use chalk\ChalkLogger;
use chalk\handler\ConsoleHandler;
use chalk\formatter\PrettyConsoleFormatter;

$logger = new ChalkLogger();
$formatter = new PrettyConsoleFormatter(showRemainingContext: true);
$handler = new ConsoleHandler($this->getLogger(), $formatter);
$logger->addHandler($handler);

$logger->info('Плагин загружен');
$logger->warning('Низкий запас памяти', ['memory' => memory_get_usage()]);
$logger->error('Ошибка подключения к БД', ['db' => 'mysql']);
```

### 2. Логирование в файл в формате JSON
```php
use chalk\handler\FileHandler;
use chalk\formatter\JsonFormatter;

$fileHandler = new FileHandler(
    'app.json',
    new JsonFormatter()
);
$logger->addHandler($fileHandler);

$logger->info('Пользователь вошёл', ['user' => 'Alice', 'ip' => '192.168.1.1']);
```

### 3. Кастомное форматирование через замыкание

```php
use chalk\formatter\CallableFormatter;

$customFormatter = new CallableFormatter(
    static function($level, $message, $extra) {
        return sprintf(
            '[%s] %s: %s',
            $extra['date'],
            strtoupper($level->name),
            $message->interpolate()
        );
    }
);
$handler = new ConsoleHandler($this->getLogger(), $customFormatter);
$logger->addHandler($handler);
```

### 4. Использование форматтера из частей (Builder)

```php
use chalk\formatter\builder\FormatterBuilder;
use chalk\style\ConsoleColor;
use chalk\style\Style;
use chalk\handler\LogLevel;

$formatter = (new FormatterBuilder())
    ->addDatePart('[', ']', null, new Style(ConsoleColor::CYAN))
    ->addLiteral(' ')
    ->addLevelPart('[', ']', new Style(ConsoleColor::MAGENTA), [
        LogLevel::INFO->value  => new Style(ConsoleColor::GREEN),
        LogLevel::WARNING->value => new Style(ConsoleColor::YELLOW),
        LogLevel::ERROR->value => new Style(ConsoleColor::RED),
    ])
    ->addLiteral(' ')
    ->addMessagePart(new Style(ConsoleColor::WHITE))
    ->addRemainingContextPart(
        new JsonStyle(
            keyColor: ConsoleColor::CYAN,
            stringColor: ConsoleColor::WHITE
        ),
        true
    )
    ->build();

$handler = new ConsoleHandler($this->getLogger(), $formatter);
$logger->addHandler($handler);
```

### 5. Градиенты на частях лога
```php
use chalk\style\RgbColor;
use chalk\formatter\builder\FormatterBuilder;

$formatter = (new FormatterBuilder())
    ->addGradientDatePart(
        new RgbColor(100, 200, 255), // светло-голубой
        new RgbColor(30, 100, 200),  // тёмно-синий
        '[', ']', null, true
    )
    ->addLiteral(' ')
    ->addGradientLevelPart(
        new RgbColor(255, 150, 200), // розовый
        new RgbColor(255, 100, 50),  // оранжевый
        '[', ']', null, true
    )
    ->addLiteral(' ')
    ->addGradientMessagePart(
        new RgbColor(150, 255, 150), // салатовый
        new RgbColor(0, 150, 100)    // изумрудный
    )
    ->build();

$handler = new ConsoleHandler($this->getLogger(), $formatter);
$logger->addHandler($handler);
$logger->info('Привет, мир!');
```

### 6. Кастомизация цвета скобок и текста уровня отдельно
```php
$formatter = (new FormatterBuilder())
    ->addDatePart('[', ']', null, new Style(ConsoleColor::CYAN))
    ->addLiteral(' ')
    ->addLiteral('(', new Style(ConsoleColor::MAGENTA))
    ->addGradientLevelPart(
        new RgbColor(255, 150, 200),
        new RgbColor(255, 100, 50),
        '', '' // убираем скобки в самой части уровня
    )
    ->addLiteral(')', new Style(ConsoleColor::MAGENTA))
    ->addLiteral(' ')
    ->addMessagePart()
    ->build();
```

### 7. Логирование с контекстом и интерполяцией
```php
$logger->info('Пользователь {user} зашёл с IP {ip}', [
    'user' => 'Steve',
    'ip' => '10.0.0.1',
    'extra_info' => ['level' => 5, 'guild' => 'builders'] // неиспользуемые ключи будут показаны, если включено showRemainingContext
]);
```

### 8. Логирование исключений
```php
try {
    // какой-то код
} catch (\Throwable $e) {
    $logger->error('Исключение: ' . $e->getMessage(), [
        'exception' => $e
    ]);
    // или используйте встроенный метод logException, если он есть
    // $logger->logException($e);
}
```

### 10. Создание своего форматтера
```php
use chalk\formatter\ContextInterpolator;
use chalk\LogLevel;

class UppercaseFormatter extends ContextInterpolator
{
    protected function doFormat(LogLevel $level, string $finalMessage, array $extra): string
    {
        return strtoupper($finalMessage);
    }
}

$formatter = new UppercaseFormatter(showRemainingContext: true);
$handler = new ConsoleHandler($this->getLogger(), $formatter);
$logger->addHandler($handler);
$logger->info('Hello World'); // выведет "HELLO WORLD"
```

