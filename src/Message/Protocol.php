<?php

namespace Bauhaus\Http\Message;

use Bauhaus\Http\Message\ProtocolInterface;

class Protocol implements ProtocolInterface
{
    const VERSION_1_0 = '1.0';
    const VERSION_1_1 = '1.1';

    private $versionNumber = null;

    public function __construct(string $versionNumber)
    {
        $this->versionNumber = $versionNumber;
    }

    public function versionNumber(): string
    {
        return $this->versionNumber;
    }
}
