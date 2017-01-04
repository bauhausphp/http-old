<?php

namespace Bauhaus\Http\Message;

class HeaderField implements HeaderFieldInterface
{
    private $name = null;
    private $arrayValue = null;

    public function __construct(string $name, $value)
    {
        $arrayValue = $this->transformValueToArray($value);

        $this->name = $name;
        $this->arrayValue = $arrayValue;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function valueAsArray(): array
    {
        return $this->arrayValue;
    }

    public function valueAsString(): string
    {
        return implode(', ', $this->arrayValue);
    }

    public function appendValue($value): HeaderFieldInterface
    {
        $newValue = array_merge(
            $this->valueAsArray(),
            $this->transformValueToArray($value)
        );

        return new HeaderField($this->name(), $newValue);
    }

    private function transformValueToArray($value): array
    {
        if (is_string($value)) {
            return [$value];
        } elseif (is_array($value)) {
            return $value;
        }
    }
}
