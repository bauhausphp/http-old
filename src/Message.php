<?php

namespace Bauhaus\Http;

use Psr\Http\Message\StreamInterface;
use Bauhaus\Http\MessageInterface;
use Bauhaus\Http\Message\Protocol;
use Bauhaus\Http\Message\ProtocolInterface;
use Bauhaus\Http\Message\HeaderContainer;
use Bauhaus\Http\Message\HeaderContainerInterface;
use Bauhaus\Http\Response;
use Bauhaus\Http\Response\StatusInterface;

abstract class Message implements MessageInterface
{
    const DEFAULT_PROTOCOL_VERSION = Protocol::VERSION_1_1;

    private $protocol = null;
    private $headers = null;

    public function __construct(
        ProtocolInterface $protocol = null,
        HeaderContainerInterface $headers = null
    ) {
        if (null === $protocol) {
            $protocol = new Protocol(self::DEFAULT_PROTOCOL_VERSION);
        }
        if (null === $headers) {
            $headers = new HeaderContainer();
        }

        $this->protocol = $protocol;
        $this->headers = $headers;
    }

    public function withProtocolVersion($versionNumber)
    {
        return $this->withFieldUpdated(new Protocol($versionNumber));
    }

    public function getProtocolVersion()
    {
        return $this->protocol->versionNumber();
    }

    public function protocol(): ProtocolInterface
    {
        return $this->protocol;
    }

    public function getHeaders()
    {
        return $this->headers->asArray();
    }

    public function hasHeader($name)
    {
        return $this->headers->has($name);
    }

    public function getHeader($name)
    {
        return $this->headers->getValueAsArrayOf($name);
    }

    public function getHeaderLine($name)
    {
        return $this->headers->getValueAsStringOf($name);
    }

    public function withHeader($name, $value)
    {
        return $this->withFieldUpdated($this->headers->withHeader($name, $value));
    }

    public function withAddedHeader($name, $value)
    {
        return $this->withFieldUpdated($this->headers->withAddedHeader($name, $value));
    }

    public function withoutHeader($name)
    {
        return $this->withFieldUpdated($this->headers->withoutHeader($name));
    }

    public function headers(): HeaderContainerInterface
    {
        return $this->headers;
    }

    public function getBody()
    {
    }

    public function withBody(StreamInterface $body)
    {
    }

    protected function withFieldUpdated($fieldToUpdate): MessageInterface
    {
        $protocol = $fieldToUpdate instanceof ProtocolInterface ?
            $fieldToUpdate : $this->protocol();
        $headers = $fieldToUpdate instanceof HeaderContainerInterface ?
            $fieldToUpdate : $this->headers();

        if ($this instanceof Response) {
            $status = $fieldToUpdate instanceof StatusInterface ?
                $fieldToUpdate : $this->status();

            return new Response(
                $status,
                $protocol,
                $headers
            );
        }
    }
}
