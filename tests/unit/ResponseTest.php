<?php

namespace Bauhaus\Http;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function responseIsCreatedWithTheStatusCode200IfNoneIsGiven()
    {
        $response = new Response();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getReasonPhrase());
    }

    /**
     * @test
     */
    public function createResponseWithRecommendedReasonPhraseForTheGivenStatusCode()
    {
        $response = new Response();

        $newResponse = $response->withStatus(202);

        $this->assertNotSame($response, $newResponse);
        $this->assertEquals(202, $newResponse->getStatusCode());
        $this->assertEquals('Accepted', $newResponse->getReasonPhrase());
    }

    /**
     * @test
     */
    public function createResponseGivenAStatusCodeAndAReasonPhrase()
    {
        $response = new Response();

        $newResponse = $response->withStatus(202, 'Processing request');

        $this->assertNotSame($response, $newResponse);
        $this->assertEquals(202, $newResponse->getStatusCode());
        $this->assertEquals('Processing request', $newResponse->getReasonPhrase());
    }
}
