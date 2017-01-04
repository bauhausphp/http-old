<?php

namespace Bauhaus\Http;

use Bauhaus\Http\Message;
use Bauhaus\Http\Message\ProtocolInterface;
use Bauhaus\Http\Message\HeaderContainerInterface;
use Bauhaus\Http\ResponseInterface;
use Bauhaus\Http\Response\Status;
use Bauhaus\Http\Response\StatusInterface;

class Response extends Message implements ResponseInterface
{
    const DEFAULT_STATUS_CODE = 200;

    private $status = null;

    public function __construct(
        StatusInterface $status = null,
        ProtocolInterface $protocol = null,
        HeaderContainerInterface $headers = null
    ) {
        if (null === $status) {
            $status = new Status(self::DEFAULT_STATUS_CODE);
        }

        $this->status = $status;

        parent::__construct($protocol, $headers);
    }

    public function withStatus($code, $reasonPhrase = '')
    {
        return $this->withFieldUpdated(new Status($code, $reasonPhrase));
    }

    public function getStatusCode()
    {
        return $this->status->code();
    }

    public function getReasonPhrase()
    {
        return $this->status->reasonPhrase();
    }

    public function status(): StatusInterface
    {
        return $this->status;
    }
}
