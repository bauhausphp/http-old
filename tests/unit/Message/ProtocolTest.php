<?php

namespace Bauhaus\Http\Message;

use Bauhaus\Http\Message\Protocol;

class ProtocolTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The HTTP version '1.15' is not supported
     */
    public function exceptionOccursWhenAnUnsupportedVersionIsGiven()
    {
        new Protocol('1.15');
    }
}
