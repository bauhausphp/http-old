<?php

namespace Bauhaus\Http\Message;

class HeaderField implements HeaderFieldInterface
{
    private $name = null;
    private $arrayValue = null;

    public function __construct(string $name, $value)
    {
        $arrayValue = $this->transformValueToArray($value);
        // TODO validate $name
        // TODO validate $value

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

    public function withValueAppend($value): HeaderFieldInterface
    {
        return new HeaderField(
            $this->name(),
            array_merge($this->arrayValue, $this->transformValueToArray($value))
        );
    }

    public function withNewValue($value): HeaderFieldInterface
    {
        return new HeaderField($this->name(), $value);
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
