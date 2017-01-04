<?php

namespace Bauhaus\Http\Message;

class HeaderContainerTest extends \PHPUnit_Framework_TestCase
{
    private $headerContainer = null;

    protected function setUp()
    {
        $this->headerContainer = (new HeaderContainer())
            ->withHeader('Pokemon', 'Charmander')
            ->withHeader('Musical-Instruments', ['Bass', 'Drum'])
            ->withHeader('X-MyApp-Token', 'super secret');
    }

    /**
     * @test
     */
    public function returnNewContainerWithANewHeaderAdded()
    {
        $originalHeaders = $this->headerContainer->asArray();

        $newHeaderContainer = $this->headerContainer->withHeader(
            'Music',
            'Right Now'
        );

        $this->assertEquals($originalHeaders, $this->headerContainer->asArray());
        $this->assertNotSame($this->headerContainer, $newHeaderContainer);
        $this->assertTrue($newHeaderContainer->has('Music'));
        $this->assertEquals(
            'Right Now',
            $newHeaderContainer->getValueAsStringOf('Music')
        );
    }

    /**
     * @test
     * @dataProvider headersToAppend
     */
    public function returnNewContainerWithAValueAppendedToAnExistinHeader(
        string $name,
        $valueToAppend,
        string $expectedResult
    ) {
        $originalHeaders = $this->headerContainer->asArray();

        $newHeaderContainer = $this->headerContainer->withAddedHeader(
            $name,
            $valueToAppend
        );

        $this->assertEquals($originalHeaders, $this->headerContainer->asArray());
        $this->assertNotSame($this->headerContainer, $newHeaderContainer);
        $this->assertEquals(
            $expectedResult,
            $newHeaderContainer->getValueAsStringOf($name)
        );
    }

    public function headersToAppend()
    {
        return [
            [
                'Pokemon',
                'Pikachu',
                'Charmander, Pikachu'
            ],
            [
                'Pokemon',
                ['Pikachu', 'Pidgeot'],
                'Charmander, Pikachu, Pidgeot'
            ],
            [
                'Musical-Instruments',
                'Guitar',
                'Bass, Drum, Guitar'
            ],
            [
                'Musical-Instruments',
                ['Guitar', 'Keyboard'],
                'Bass, Drum, Guitar, Keyboard'
            ],
            [
                'Pirate',
                'Barbossa',
                'Barbossa'
            ],
        ];
    }

    /**
     * @test
     * @dataProvider headerNamesAndExistences
     */
    public function checkIfHeaderExistsByItsCaseInsentitiveName(
        string $name,
        bool $expectedResult
    ) {
        $this->assertTrue(
            $expectedResult === $this->headerContainer->has($name)
        );
    }

    public function headerNamesAndExistences()
    {
        return [
            ['Pokemon', true],
            ['pokemon', true],
            ['POKEMON', true],
            ['pOKEmON', true],
            ['x-myapp-token', true],
            ['Digimon', false],
        ];
    }

    /**
     * @test
     */
    public function retrieveAnArrayWithAllHeadersWithValuesAsArray()
    {
        $this->assertEquals(
            [
                'Pokemon' => ['Charmander'],
                'Musical-Instruments' => ['Bass', 'Drum'],
                'X-MyApp-Token' => ['super secret'],
            ],
            $this->headerContainer->asArray()
        );
    }

    /**
     * @test
     * @dataProvider headerNamesAndValuesAsArray
     */
    public function retrieveHeaderValueAsArrayByItsCaseInsensitiveName(
        string $name,
        array $expectedResult
    ) {
        $this->assertTrue(
            $expectedResult === $this->headerContainer->getValueAsArrayOf($name)
        );
    }

    public function headerNamesAndValuesAsArray()
    {
        return [
            ['Pokemon', ['Charmander']],
            ['pokemon', ['Charmander']],
            ['POKEMON', ['Charmander']],
            ['Musical-Instruments', ['Bass', 'Drum']],
        ];
    }

    /**
     * @test
     */
    public function returnEmptyArrayWhenTryToRetrieveValueAsArrayOfUnexistingHeader()
    {
        $this->assertEquals(
            [],
            $this->headerContainer->getValueAsArrayOf('Digimon')
        );
    }

    /**
     * @test
     * @dataProvider headerNamesAndValuesAsString
     */
    public function retrieveHeaderValueAsStringByItsCaseInsensitiveName(
        string $name,
        string $expectedResult
    ) {
        $this->assertTrue(
            $expectedResult === $this->headerContainer->getValueAsStringOf($name)
        );
    }

    public function headerNamesAndValuesAsString()
    {
        return [
            ['Pokemon', 'Charmander'],
            ['pokemon', 'Charmander'],
            ['POKEMON', 'Charmander'],
            ['Musical-Instruments', 'Bass, Drum'],
        ];
    }

    /**
     * @test
     */
    public function returnEmptyStringWhenTryToRetrieveValueAsStringOfUnexistingHeader()
    {
        $this->assertEquals(
            '',
            $this->headerContainer->getValueAsStringOf('Digimon')
        );
    }
}
