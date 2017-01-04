<?php

namespace Bauhaus\Http;

use Bauhaus\Http\Response\Status;
use Bauhaus\Http\Message\Protocol;
use Bauhaus\Http\Message\HeaderContainer;

abstract class MessageBaseTest extends \PHPUnit_Framework_TestCase
{
    protected $message = null;

    /**
     * Set up a message object to run the tests that need to prove the
     * immutability of the message implementation
     */
    protected function baseSetUp()
    {
        $this->message = $this->defaultMessageObject()
            ->withProtocolVersion('1.0')
            ->withHeader('Pokemon', 'Pikachu')
            ->withHeader('Musical-Instruments', ['Bass', 'Drum']);
    }

    /**
     * These method MUST return the default instance of the Message class that
     * is being tested: new Response() or new Request()
     */
    abstract protected function defaultMessageObject(): MessageInterface;

    /**
     * These method is used to verify if two instalces of MessageInterface are
     * not the same: if a new MessageInterface instance was created
     */
    protected function assertThatNewMessageWasCreated(
        MessageInterface $messageA,
        MessageInterface $messageB
    ) {
        $this->assertNotSame($messageA, $messageB);
    }

    /**
     * These method is used to compare all fields declared in class Message are
     * equals
     */
    protected function assertThatMessageFieldsAreEquals(
        MessageInterface $messageA,
        MessageInterface $messageB
    ) {
        $this->assertEquals($messageA->protocol(), $messageB->protocol());
        $this->assertEquals($messageA->headers(), $messageB->headers());
    }

    /**
     * These method is used to compare all fields of the classes that extends
     * the Message (Response or Request)
     */
    private function assertThatSpecificMessageFieldsAreEquals(
        MessageInterface $messageA,
        MessageInterface $messageB
    ) {
        if ($messageA instanceof Response) {
            $this->assertEquals($messageA->status(), $messageB->status());
        }
    }

    /**
     * @test
     * @testdox Create default message with protocol version 1.1
     */
    public function createDefaultMessageWithProtocolVersion1Dot1()
    {
        $defaultMessage = $this->defaultMessageObject();

        $this->assertEquals('1.1', $defaultMessage->getProtocolVersion());
    }

    /**
     * @test
     */
    public function createNewMessageWithTheProvidedProtocolVersion()
    {
        $newMessage = $this->message->withProtocolVersion('1.1');

        $this->assertThatNewMessageWasCreated($this->message, $newMessage);
        $this->assertThatSpecificMessageFieldsAreEquals($this->message, $newMessage);
        $this->assertEquals($this->message->headers(), $newMessage->headers());

        $this->assertEquals('1.1', $newMessage->getProtocolVersion());
    }

    /**
     * @test
     */
    public function createDefaultMessageWithNoHeaders()
    {
        $defaultMessage = $this->defaultMessageObject();

        $this->assertEquals([], $defaultMessage->getHeaders());
    }

    /**
     * @test
     */
    public function retrieveAllHeadersAsArray()
    {
        $expectedHeadersAsArray = [
            'Pokemon' => [
                'Pikachu',
            ],
            'Musical-Instruments' => [
                'Bass',
                'Drum'
            ],
        ];

        $this->assertEquals($expectedHeadersAsArray, $this->message->getHeaders());
    }

    /**
     * @test
     * @testdox Verify if header exists by its case-insensitive name
     * @dataProvider headerNamesAndTheirExistences
     */
    public function verifyIfHeaderExistsByItsCaseInsensitiveName()
    {
        $this->assertTrue($this->message->hasHeader('Pokemon'));
    }

    public function headerNamesAndTheirExistences()
    {
        return [
            ['Pokemon', true],
            ['pOKEMON', true],
            ['POKEMON', true],
            ['pokemon', true],
            ['pOKeMon', true],
            ['Music', false],
            ['', false],
        ];
    }

    /**
     * @test
     * @testdox Retrieve header value as array by its case-insensitive name
     * @dataProvider headerNamesAndTheirValuesAsArray
     */
    public function retrieveHeaderValueAsArrayByItsCaseInsensitiveName(
        string $name,
        array $expectedValue
    )
    {
        $this->assertEquals($expectedValue, $this->message->getHeader($name));
    }

    public function headerNamesAndTheirValuesAsArray()
    {
        return [
            ['Pokemon', ['Pikachu']],
            ['POKEMON', ['Pikachu']],
            ['pokemon', ['Pikachu']],
            ['PokEMon', ['Pikachu']],
            ['Musical-Instruments', ['Bass', 'Drum']],
            ['musical-instruments', ['Bass', 'Drum']],
            ['No-Existing-Header', []],
        ];
    }

    /**
     * @test
     * @testdox Retrieve header value as comma-separated string by its case-insensitive name
     * @dataProvider headerNamesAndTheirValuesAsCommaSeparatedString
     */
    public function retrieveHeaderValueAsCommaSeparatedByItsCaseInsensitiveName(
        string $name,
        string $expectedValue
    )
    {
        $this->assertEquals($expectedValue, $this->message->getHeaderLine($name));
    }

    public function headerNamesAndTheirValuesAsCommaSeparatedString()
    {
        return [
            ['Pokemon', 'Pikachu'],
            ['POKEMON', 'Pikachu'],
            ['pokemon', 'Pikachu'],
            ['PokEMon', 'Pikachu'],
            ['Musical-Instruments', 'Bass, Drum'],
            ['musical-instruments', 'Bass, Drum'],
            ['No-Existing-Header', ''],
        ];
    }

    /**
     * @test
     * @dataProvider newHeaderNamesAndValues
     */
    public function createNewMessageWithHeaderReplacedOrCreated(
        string $headerName,
        $headerValue,
        string $expectedKeyOfGetHeaders,
        array $expectedValueOfGetHeaders
    ) {
        $newMessage = $this->message->withHeader($headerName, $headerValue);

        $this->assertThatNewMessageWasCreated($this->message, $newMessage);
        $this->assertThatSpecificMessageFieldsAreEquals($this->message, $newMessage);
        $this->assertEquals($this->message->protocol(), $newMessage->protocol());

        $this->assertArrayHasKey(
            $expectedKeyOfGetHeaders,
            $newMessage->getHeaders()
        );
        $this->assertEquals(
            $expectedValueOfGetHeaders,
            $newMessage->getHeaders()[$expectedKeyOfGetHeaders]
        );
    }

    public function newHeaderNamesAndValues()
    {
        return [
            [
                'Pokemon',
                'Charmander',
                'Pokemon',
                ['Charmander'],
            ],
            [
                'pOKEmon',
                'Charmander',
                'Pokemon',
                ['Charmander'],
            ],
            [
                'pokemon',
                ['Charmander', 'Pideot'],
                'Pokemon',
                ['Charmander', 'Pideot'],
            ],
            [
                'Pirate',
                'Barbossa',
                'Pirate',
                ['Barbossa'],
            ],
            [
                'pirate',
                ['Barbossa', 'Spaerrow'],
                'pirate',
                ['Barbossa', 'Spaerrow'],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider newValuesToAppendInHeaders
     */
    public function createNewMessageWithValueAppendedToExistingHeaderOrNewHeaderCreated(
        string $headerName,
        $headerValue,
        string $expectedKeyOfGetHeaders,
        array $expectedValueOfGetHeaders
    ) {
        $newMessage = $this->message->withAddedHeader($headerName, $headerValue);

        $this->assertThatNewMessageWasCreated($this->message, $newMessage);
        $this->assertThatSpecificMessageFieldsAreEquals($this->message, $newMessage);
        $this->assertEquals($this->message->protocol(), $newMessage->protocol());

        $this->assertArrayHasKey(
            $expectedKeyOfGetHeaders,
            $newMessage->getHeaders()
        );
        $this->assertEquals(
            $expectedValueOfGetHeaders,
            $newMessage->getHeaders()[$expectedKeyOfGetHeaders]
        );
    }

    public function newValuesToAppendInHeaders()
    {
        return [
            [
                'Pokemon',
                'Charmander',
                'Pokemon',
                ['Pikachu', 'Charmander'],
            ],
            [
                'Pokemon',
                ['Charmander', 'Pideot'],
                'Pokemon',
                ['Pikachu', 'Charmander', 'Pideot'],
            ],
            [
                'POKEMON',
                ['Charmander', 'Pideot'],
                'Pokemon',
                ['Pikachu', 'Charmander', 'Pideot'],
            ],
            [
                'musical-instruments',
                'Guitar',
                'Musical-Instruments',
                ['Bass', 'Drum', 'Guitar'],
            ],
            [
                'Musical-Instruments',
                ['Guitar', 'Keyboard'],
                'Musical-Instruments',
                ['Bass', 'Drum', 'Guitar', 'Keyboard'],
            ],
            [
                'pirate',
                'Barbossa',
                'pirate',
                ['Barbossa'],
            ],
            [
                'Pirate',
                ['Barbossa', 'Sparrow'],
                'Pirate',
                ['Barbossa', 'Sparrow'],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider headerNamesToRemove
     */
    public function createNewMessageWithoutHeader(string $headerName)
    {
        $newMessage = $this->message->withoutHeader($headerName);

        $this->assertThatNewMessageWasCreated($this->message, $newMessage);
        $this->assertThatSpecificMessageFieldsAreEquals($this->message, $newMessage);
        $this->assertEquals($this->message->protocol(), $newMessage->protocol());

        $this->assertFalse($newMessage->hasHeader($headerName));
    }

    public function headerNamesToRemove()
    {
        return [
            ['Musical-Instruments'],
            ['No-Existing-Header'],
        ];
    }
}
