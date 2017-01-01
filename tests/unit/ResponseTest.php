<?php

namespace Bauhaus\Http;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function createReponseWithStatusCode200IfItWasNotInformed()
    {
        $response = new Response();

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function createNewResponseWithTheDesiredStatusCode()
    {
        $response = new Response();

        $newResponse = $response->withStatus(202);

        $this->assertNotSame($response, $newResponse);
        $this->assertEquals(202, $newResponse->getStatusCode());
    }
}
