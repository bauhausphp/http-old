<?php

namespace Bauhaus\Http;

use Psr\Http\Message\ResponseInterface;
use Bauhaus\Http\Message;
use Bauhaus\Http\Response\Status;
use Bauhaus\Http\Response\StatusInterface;

class Response extends Message implements ResponseInterface
{
    private $status = null;

    public function __construct(StatusInterface $status = null)
    {
        if (null === $status) {
            $status = new Status(200);
        }

        $this->status = $status;
    }

    public function withStatus($code, $reasonPhrase = '')
    {
        $status = new Status($code, $reasonPhrase);

        return new self($status);
    }

    public function getStatusCode()
    {
        return $this->status->code();
    }

    public function getReasonPhrase()
    {
        return $this->status->reasonPhrase();
    }
}
