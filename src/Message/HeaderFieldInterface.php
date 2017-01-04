<?php

namespace Bauhaus\Http\Message;

interface HeaderFieldInterface
{
    public function name(): string;
    public function valueAsArray(): array;
    public function valueAsString(): string;
    public function appendValue($value): HeaderFieldInterface;
}
