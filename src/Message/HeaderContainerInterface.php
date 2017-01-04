<?php

namespace Bauhaus\Http\Message;

interface HeaderContainerInterface
{
    public function has($name);
    public function getValueAsArrayOf(string $name): array;
    public function getValueAsStringOf(string $name): string;
    public function asArray(): array;
    public function withHeader(string $name, $value): HeaderContainerInterface;
    public function withAddedHeader(string $name, $value): HeaderContainerInterface;
    public function withoutHeader(string $name): HeaderContainerInterface;
}
