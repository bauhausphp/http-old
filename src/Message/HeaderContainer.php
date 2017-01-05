<?php

namespace Bauhaus\Http\Message;

use Bauhaus\Container;
use Bauhaus\Container\Factory;
use Bauhaus\Container\ItemNotFoundException;
use Bauhaus\Http\Message\HeaderContainerInterface;
use Bauhaus\Http\Message\HeaderField;

class HeaderContainer extends Container implements HeaderContainerInterface
{
    public function has($name)
    {
        $label = $this->generateCaseInsensitiveHeaderName($name);

        return parent::has($label);
    }

    public function get($name)
    {
        $label = $this->generateCaseInsensitiveHeaderName($name);

        return parent::get($label);
    }

    public function getValueAsArrayOf(string $name): array
    {
        $header = $this->get($name);

        return null === $header ? [] : $header->valueAsArray();
    }

    public function getValueAsStringOf(string $name): string
    {
        $header = $this->get($name);

        return null === $header ? '' : $header->valueAsString();
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
        $label = $this->generateCaseInsensitiveHeaderName($name);

        if (false === $this->has($name)) {
            $newHeader = new HeaderField($name, $value);

            return $this->factory()->containerWithItemAdded($label, $newHeader);
        }

        $currentHeader = $this->get($name);
        $newHeader = $currentHeader->withNewValue($value);

        return $this->factory()->containerWithItemReplaced($label, $newHeader);
    }

    public function withAddedHeader(string $name, $value): HeaderContainerInterface
    {
        $label = $this->generateCaseInsensitiveHeaderName($name);

        if (false === $this->has($name)) {
            $newHeader = new HeaderField($name, $value);

            return $this->factory()->containerWithItemAdded($label, $newHeader);
        }

        $currentHeader = $this->get($name);
        $newHeader = $currentHeader->withValueAppend($value);

        return $this->factory()->containerWithItemReplaced($label, $newHeader);
    }

    public function withoutHeader(string $name): HeaderContainerInterface
    {
        if (false === $this->has($name)) {
            return $this;
        }

        $label = $this->generateCaseInsensitiveHeaderName($name);

        return $this->factory()->containerWithoutItem($label);
    }

    protected function itemNotFoundHandler(string $label)
    {
        return null;
    }

    private function generateCaseInsensitiveHeaderName(string $name): string
    {
        return strtolower($name);
    }

    private function factory(): Factory
    {
        return new Factory($this);
    }
}
