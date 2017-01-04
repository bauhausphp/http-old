<?php

namespace Bauhaus\Http\Message;

use Bauhaus\Container;
use Bauhaus\Container\Factory as ContainerFactory;
use Bauhaus\Container\ItemNotFoundException;
use Bauhaus\Http\Message\HeaderContainerInterface;
use Bauhaus\Http\Message\HeaderField;

class HeaderContainer extends Container implements HeaderContainerInterface
{
    public function has($name)
    {
        return parent::has($this->generateCaseInsensitiveHeaderName($name));
    }

    public function get($name)
    {
        return parent::get($this->generateCaseInsensitiveHeaderName($name));
    }

    public function getValueAsArrayOf(string $name): array
    {
        try {
            $header = $this->get($name);
        } catch (ItemNotFoundException $e) {
            return [];
        }

        return $header->valueAsArray();
    }

    public function getValueAsStringOf(string $name): string
    {
        try {
            $header = $this->get($name);
        } catch (ItemNotFoundException $e) {
            return '';
        }

        return $header->valueAsString();
    }

    public function asArray(): array
    {
        $arrayToReturn = [];
        foreach ($this->items() as $header) {
            $arrayToReturn[$header->name()] = $header->valueAsArray();
        }

        return $arrayToReturn;
    }

    public function withHeader(string $name, $value): HeaderContainerInterface
    {
        if (false === $this->has($name)) {
            return $this->containerFactory()->containerWithItemAdded(
                $this,
                $this->generateCaseInsensitiveHeaderName($name),
                new HeaderField($name, $value)
            );
        }

        $newHeader = $this->get($name)->withNewValue($value);

        return $this->containerFactory()->containerWithItemReplaced(
            $this,
            $this->generateCaseInsensitiveHeaderName($name),
            $newHeader
        );
    }

    public function withAddedHeader(string $name, $value): HeaderContainerInterface
    {
        if (false === $this->has($name)) {
            return $this->withHeader($name, $value);
        }

        $newHeader = $this->get($name)->withValueAppend($value);

        return $this->containerFactory()->containerWithItemReplaced(
            $this,
            $this->generateCaseInsensitiveHeaderName($name),
            $newHeader
        );
    }

    public function withoutHeader(string $name): HeaderContainerInterface
    {
        if (false === $this->has($name)) {
            return $this;
        }

        return $this->containerFactory()->containerWithoutItem(
            $this,
            $this->generateCaseInsensitiveHeaderName($name)
        );
    }

    private function generateCaseInsensitiveHeaderName(string $name): string
    {
        return strtolower($name);
    }

    private function containerFactory(): ContainerFactory
    {
        return new ContainerFactory();
    }
}
