<?php

namespace Bauhaus\Http;

class ResponseTest extends MessageBaseTest
{
    private $response = null;

    protected function setUp()
    {
        $this->baseSetUp();

        $this->response = $this->message
            ->withStatus(100);
    }

    protected function defaultMessageObject(): MessageInterface
    {
        return new Response();
    }

    /**
     * @test
     */
    public function responseIsCreatedWithTheStatusCode200AsDefault()
    {
        $response = new Response();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getReasonPhrase());
    }

    /**
     * @test
     */
    public function createNewResponseGivenAStatusCodeExpectingItsRecommendedReasonPhrase()
    {
        $newResponse = $this->response->withStatus(202);

        $this->assertThatNewMessageWasCreated($this->response, $newResponse);
        $this->assertThatMessageFieldsAreEquals($this->response, $newResponse);

        $this->assertEquals(202, $newResponse->getStatusCode());
        $this->assertEquals('Accepted', $newResponse->getReasonPhrase());
    }

    /**
     * @test
     */
    public function createNewResponseGivenAStatusCodeAndAReasonPhrase()
    {
        $newResponse = $this->response->withStatus(202, 'Processing request');

        $this->assertThatNewMessageWasCreated($this->response, $newResponse);
        $this->assertThatMessageFieldsAreEquals($this->response, $newResponse);

        $this->assertEquals(202, $newResponse->getStatusCode());
        $this->assertEquals('Processing request', $newResponse->getReasonPhrase());
    }
}
