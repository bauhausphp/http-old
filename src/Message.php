<?php

namespace Bauhaus\Http;

use Psr\Http\Message\StreamInterface;
use Bauhaus\Http\MessageInterface;
use Bauhaus\Http\Message\Protocol;
use Bauhaus\Http\Message\ProtocolInterface;

abstract class Message implements MessageInterface
{
    const DEFAULT_PROTOCOL_VERSION = Protocol::VERSION_1_1;

    private $protocol = null;

    public function __construct(ProtocolInterface $protocol = null)
    {
        if (null === $protocol) {
            $protocol = new Protocol(self::DEFAULT_PROTOCOL_VERSION);
        }

        $this->protocol = $protocol;
    }

    public function withProtocolVersion($versionNumber)
    {
        $protocol = new Protocol($versionNumber);

        return $this->createNewMessage($protocol);
    }

    public function getProtocolVersion()
    {
        return $this->protocol->versionNumber();
    }

    public function getHeaders()
    {
    }

    public function hasHeader($name)
    {
    }

    public function getHeader($name)
    {
    }

    public function getHeaderLine($name)
    {
    }

    public function withHeader($name, $value)
    {
    }

    public function withAddedHeader($name, $value)
    {
    }

    public function withoutHeader($name)
    {
    }

    public function getBody()
    {
    }

    public function withBody(StreamInterface $body)
    {
    }

    private function createNewMessage(Protocol $protocol): MessageInterface
    {
        return new Response($this->status(), $protocol);
    }
}
