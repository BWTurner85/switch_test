<?php
namespace Acme\test\unit;

use Acme\Cli\OptionsManager;
use PHPUnit\Framework\TestCase;

class OptionsManagerTest extends TestCase
{
    public function duplicateParamProvider()
    {
        return [
            // Duplicated genre params
            [ [ 'g' => [ 'Comedy', 'Not Comedy' ] ] ],
            [ [ 'genre' => [ 'Action', 'Drama' ] ] ],
            [ [ 'g' => 'Animation', 'genre' => 'Black and white spanish movies with subtitles' ] ],

            // Duplicated time params
            [ [ 't' => [ '12:00', '15:00' ] ] ],
            [ [ 'time' => [ '9:00', '19:00' ] ] ],
            [ [ 't' => '21:30', 'time' => '00:45' ] ],
        ];
    }

    /**
     * @param array $options
     * @dataProvider duplicateParamProvider
     * @expectedException \Exception
     * @expectedExceptionMessage Duplicated param
     */
    public function testValidateFailsOnDuplicatedParams(array $options)
    {
        (new OptionsManager($options))->validate();
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Missing required param
     */
    public function testValidateFailsOnMissingGenreProvider()
    {
        $options = [ 't' => '1:00' ];
        (new OptionsManager($options))->validate();
    }

    public function validOptionsProvider()
    {
        return [
            [ [ 'g' => 'Drama' ] ],
            [ [ 'g' => 'Comedy', 't' => '12:00' ] ],
            [ [ 'genre' => 'Action' ] ],
            [ [ 'genre' => 'Action', 'time' => '10:00' ] ]
        ];
    }

    /**
     * @param array $options
     * @dataProvider validOptionsProvider
     */
    public function testValidateWorksWithValidOptions($options)
    {
        $this->assertNull((new OptionsManager($options))->validate());
    }

    public function genreParamProvider()
    {
        return [ [ 'Comedy'] , [ 'Drama' ] ];
    }

    /**
     * @param string $genre
     * @dataProvider genreParamProvider
     */
    public function testShortGenreParamIsCorrectlyRecognised(string $genre)
    {
        $manager = new OptionsManager([ 'g' => $genre ]);

        $this->assertEquals($genre, $manager->getGenre());
    }

    /**
     * @param string $genre
     * @dataProvider genreParamProvider
     */
    public function testLongGenreParamIsCorrectlyRecognised(string $genre)
    {
        $manager = new OptionsManager([ 'genre' => $genre ]);

        $this->assertEquals($genre, $manager->getGenre());
    }

    public function timeParamProvider()
    {
        return [ [ '12:00' ], [ '19:30' ] ];
    }

    /**
     * @param string $time
     * @dataProvider timeParamProvider
     */
    public function testShortTimeParamIsCorrectlyRecognised(string $time)
    {
        $manager = new OptionsManager([ 't' => $time ]);

        $this->assertEquals($time, $manager->getTime());
    }

    /**
     * @param string $time
     * @dataProvider timeParamProvider
     */
    public function testLongTimeParamIsCorrectlyRecognised(string $time)
    {
        $manager = new OptionsManager([ 'time' => $time ]);

        $this->assertEquals($time, $manager->getTime());
    }

    public function testGetTimeReturnsNullWhenNotSpecified()
    {
        $manager = new OptionsManager([ ]);

        $this->assertNull($manager->getTime());
    }
}

