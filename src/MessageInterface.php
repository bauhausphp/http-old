<?php

namespace Bauhaus\Http;

use Psr\Http\Message\MessageInterface as PsrMessageInterface;
use Bauhaus\Http\Message\ProtocolInterface;
use Bauhaus\Http\Message\HeaderContainerInterface;

interface MessageInterface extends PsrMessageInterface
{
    public function protocol(): ProtocolInterface;
    public function headers(): HeaderContainerInterface;
}
