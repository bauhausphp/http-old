<?php

namespace Bauhaus\Http;

use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Bauhaus\Http\Response\StatusInterface;

interface ResponseInterface extends PsrResponseInterface
{
    public function status(): StatusInterface;
}
