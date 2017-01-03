<?php

namespace Bauhaus\Http;

use Bauhaus\Http\Response;
use Bauhaus\Http\Message\Protocol;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    private $message = null;

    protected function setUp()
    {
        $this->message = new Response();
    }

    /**
     * @test
     * @testdox Message is created with protocol version 1.1 if none is given
     */
    public function messageIsCreatedWithProtocolVersion1Dot1IfNoneIsGiven()
    {
        $this->assertEquals('1.1', $this->message->getProtocolVersion());
    }

    /**
     * @test
     */
    public function createMessageWithTheGivenProtocolVersion()
    {
        // Arrange
        $immutableMessage = $this->message;

        // Act
        $newMessage = $immutableMessage->withProtocolVersion('1.0');

        // Assert
        $this->assertEquals($immutableMessage, $this->message); // immutable message
        $this->assertNotSame($this->message, $newMessage); // new message returned

        $this->assertEquals('1.0', $newMessage->getProtocolVersion());

        $this->assertEquals($this->message->status(), $newMessage->status());
    }
}
