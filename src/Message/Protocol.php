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
        if ($this->isAnUnsupportedVersion($versionNumber)) {
            throw new \InvalidArgumentException(
                "The HTTP version '$versionNumber' is not supported"
            );
        }

        $this->versionNumber = $versionNumber;
    }

    public function versionNumber(): string
    {
        return $this->versionNumber;
    }

    private function isAnUnsupportedVersion(string $versionNumber): bool
    {
        return
            self::VERSION_1_0 != $versionNumber &&
            self::VERSION_1_1 != $versionNumber;
    }
}
