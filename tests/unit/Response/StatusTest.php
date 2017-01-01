<?php

namespace Bauhaus\Http\Response;

class StatusTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function createAResponseStatusByGivenTheACodeAndAReasonPhrase()
    {
        $status = new Status(201, 'Resource created');

        $this->assertEquals(201, $status->code());
        $this->assertEquals('Resource created', $status->reasonPhrase());
    }

    /**
     * @test
     * @dataProvider codesAndRecommendedReasonPhrases
     */
    public function whenNoReasonPhraseIsInformedChooseTheRecommendedOneAccordingToTheGivenCode(
        int $code,
        string $expectedReasonPhrase
    ) {
        $status = new Status($code);

        $this->assertEquals($expectedReasonPhrase, $status->reasonPhrase());
    }

    public function codesAndRecommendedReasonPhrases()
    {
        return [
            [100, 'Continue'],
            [101, 'Switching Protocols'],
            [200, 'OK'],
            [201, 'Created'],
            [202, 'Accepted'],
            [203, 'Non-Authoritative Information'],
            [204, 'No Content'],
            [205, 'Reset Content'],
            [206, 'Partial Content'],
            [300, 'Multiple Choices'],
            [301, 'Moved Permanently'],
            [302, 'Found'],
            [303, 'See Other'],
            [304, 'Not Modified'],
            [305, 'Use Proxy'],
            [307, 'Temporary Redirect'],
            [400, 'Bad Request'],
            [401, 'Unauthorized'],
            [402, 'Payment Required'],
            [403, 'Forbidden'],
            [404, 'Not Found'],
            [405, 'Method Not Allowed'],
            [406, 'Not Acceptable'],
            [407, 'Proxy Authentication Required'],
            [408, 'Request Timeout'],
            [409, 'Conflict'],
            [410, 'Gone'],
            [411, 'Length Required'],
            [412, 'Precondition Failed'],
            [413, 'Payload Too Large'],
            [414, 'URI Too Long'],
            [415, 'Unsupported Media Type'],
            [416, 'Range Not Satisfiable'],
            [417, 'Expectation Failed'],
            [426, 'Upgrade Required'],
            [500, 'Internal Server Error'],
            [501, 'Not Implemented'],
            [502, 'Bad Gateway'],
            [503, 'Service Unavailable'],
            [504, 'Gateway Timeout'],
            [505, 'HTTP Version Not Supported'],
        ];
    }

    /**
     * @test
     */
    public function emptyReasonPhraseIsSetIfNoOneIsGivenAndTheCodeDoesNotHaveADefault()
    {
        $status = new Status(499);

        $this->assertEquals('', $status->reasonPhrase());
    }

    /**
     * @test
     * @dataProvider invalidCodes
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp /The given code '.+' is invalid for response status/
     */
    public function exceptionOccursWhenTheGivenCodeIsInvalid(int $invalidCode)
    {
        new Status($invalidCode);
    }

    public function invalidCodes()
    {
        return [
            [2],
            [1],
            [0],
            [21],
            [99],
            [600],
            [601],
            [750],
            [4001],
            [-3],
            [-30],
        ];
    }
}
