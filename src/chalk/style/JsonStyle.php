<?php

declare(strict_types=1);

namespace chalk\style;
readonly class JsonStyle{
    public function __construct(
        public ?ColorInterface $braceColor = null,
        public ?ColorInterface $bracketColor = null,
        public ?ColorInterface $keyColor = null,
        public ?ColorInterface $stringColor = null,
        public ?ColorInterface $numberColor = null,
        public ?ColorInterface $boolColor = null,
        public ?ColorInterface $nullColor = null,
        public ?ColorInterface $quoteColor = null,
        public ?ColorInterface $colonColor = null,
        public ?ColorInterface $commaColor = null,
		public ?ColorInterface $objectColor = null,
    ) {}

    /**
     * Преобразует значение в цветную строку JSON.
     */
    public function formatJson(mixed $data): string{
        return $this->formatValue($data);
    }

    private function formatValue($value): string{
        if (is_null($value)) {
            return $this->apply($this->nullColor, 'null');
        }
        if (is_bool($value)) {
            return $this->apply($this->boolColor, $value ? 'true' : 'false');
        }
        if (is_numeric($value)) {
            return $this->apply($this->numberColor, (string)$value);
        }
        if (is_string($value)) {
            return $this->apply($this->quoteColor, '"')
                . $this->apply($this->stringColor, $value)
                . $this->apply($this->quoteColor, '"');
        }
		if (is_object($value)) {
			return $this->apply($this->quoteColor, '[object:')
				. $this->apply($this->objectColor, $value)
				. $this->apply($this->quoteColor, ']');
		}
        if (is_array($value)) {
            $isList = array_is_list($value);
            $openBracket = $isList ? '[' : '{';
            $closeBracket = $isList ? ']' : '}';
            $color = $isList ? $this->bracketColor : $this->braceColor;
            $separator = $this->apply($this->commaColor, ',') . ' ';

            $items = [];
            foreach ($value as $k => $v) {
                if ($isList) {
                    $items[] = $this->formatValue($v);
                } else {
                    $keyStr = $this->apply($this->quoteColor, '"')
                        . $this->apply($this->keyColor, (string)$k)
                        . $this->apply($this->quoteColor, '"')
                        . $this->apply($this->colonColor, ':') . ' '
                        . $this->formatValue($v);
                    $items[] = $keyStr;
                }
            }

            return $this->apply($color, $openBracket)
                . implode($separator, $items)
                . $this->apply($color, $closeBracket);
        }
        return json_encode($value);
    }

    private function apply(?ColorInterface $color, string $text): string{
        if ($color === null) {
            return $text;
        }
        $code = $color instanceof ConsoleColor ? $color->value : $color;
        return $code . $text . ConsoleColor::RESET->value;
    }
}