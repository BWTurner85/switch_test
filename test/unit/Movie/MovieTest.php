<?php
namespace Acme\test\unit;

use Acme\Movie\Movie;
use PHPUnit\Framework\TestCase;

class MovieTest extends TestCase
{
    public function setNameProvider()
    {
        return [ [ 'Shaun The Sheep' ], [ 'Zootopia' ] ];
    }

    /**
     * @param string $name
     * @dataProvider setNameProvider
     */
    public function testSetAndAndGetName(string $name)
    {
        $movie = new Movie();

        $this->assertEquals($movie, $movie->setName($name));
        $this->assertEquals($name, $movie->getName());
    }

    public function setInvalidRatingProvider()
    {
        return [ [ -1 ], [ 101 ] ];
    }

    /**
     * @param int $rating
     * @dataProvider  setInvalidRatingProvider
     * @expectedException \Exception
     * @expectedExceptionMessage Invalid rating supplied
     */
    public function testSetRatingFailsOnInvalidInput(int $rating)
    {
        (new Movie())->setRating($rating);
    }

    public function setRatingProvider()
    {
        // Edge cases
        $tests = [ [ 0 ], [ 100 ] ];

        // Couple of random valid inputs
        for ($i = 0; $i < 3; $i++) {
            $tests[] = [ rand(1, 99) ];
        }

        return $tests;
    }

    /**
     * @param int $rating
     * @dataProvider setRatingProvider
     */
    public function testSetAndGetValidRating(int $rating)
    {
        $movie = new Movie();

        $this->assertEquals($movie,  $movie->setRating($rating));
        $this->assertEquals($rating, $movie->getRating());
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage All genres must be strings
     */
    public function testSetGenresFailsOnInvalidGenre()
    {
        $genres = [ 'Comedy', 'Not Comedy', PHP_INT_MAX ];

        (new Movie())->setGenres($genres);
    }

    public function setValidGenresProvider()
    {
        return [
            [ [ 'Comedy' ], [ 'Drama' ] ],
            [ [ 'Action' ], [ 'Adventure' ] ]
        ];
    }

    /**
     * @param String[] $genres
     * @dataProvider setValidGenresProvider
     */
    public function testSetAndGetValidGenres(array $genres)
    {
        $movie = new Movie();

        $this->assertEquals($movie, $movie->setGenres($genres));
        $this->assertEquals($genres, $movie->getGenres());
    }

    public function testHasGenre()
    {
        $movie = new Movie();

        $movie->setGenres( [ 'Animation', 'Comedy', 'Action' ] );
        $this->assertTrue($movie->hasGenre('Animation'));
        $this->assertTrue($movie->hasGenre('Comedy'));
        $this->assertTrue($movie->hasGenre('Action'));
        $this->assertFalse($movie->hasGenre('Drama'));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage An unrecognisable showing time was provided
     */
    public function testSetShowingsFailsWithUnrecognisableTime()
    {
        (new Movie())->setShowings([ '12:00', 'sometime tonight' ]);
    }

    public function setShowingsProvider()
    {
        return [
            [ [ '12:00', '13:30', '15:25:19' ], 'H:i:s', [ '12:00:00', '13:30:00', '15:25:19' ] ],
            [ [ '5:00', '2:30', '1am' ],        'H:i',   [ '01:00', '02:30', '05:00' ] ],
        ];
    }

    /**
     * @param array $input
     * @param string $output_format
     * @param array $expected
     * @dataProvider setShowingsProvider
     */
    public function testSetAndGetShowings(array $input, string $output_format, array $expected)
    {
        $movie = new Movie();

        $this->assertequals($movie, $movie->setShowings($input));
        $this->assertEquals($expected, $movie->getShowings($output_format));
    }

    public function showsBetweenProvider()
    {
        return [
            [ '1:00',  '2:00',   false, null ],
            [ '9am',   '11am',   true, '10am' ],
            [ '08:30', '19:00',  true, '10am' ],
            [ null,    '5:00',   false, null  ],
            [ null,    '12:00',  true, '10am' ],
            [ '2pm',    null,    true,  '3pm' ],
            [ '5pm',    null,    false, null ],
            [ '10:00',  '10:05', true, '10am' ],
            [ '9:55',   '10:00', true, '10am' ],

        ];
    }

    /**
     * @param string|null $start    provided as a string - we convert to unix within the test
     * @param string|null $end      provided as a string - we convert to unix within the test
     * @param bool $expected
     * @param string $matched_showing   provided as a string - we convert to unix sithin the test
     * @dataProvider showsBetweenProvider
     */
    public function testShowsBetween($start, $end, $expected, $matched_showing)
    {
        $movie = new Movie();

        $movie->setShowings([ '10am', '3pm' ]);

        $this->assertEquals($expected, $movie->showsBetween(strtotime($start), strtotime($end)));
        $this->assertEquals(strtotime($matched_showing), $movie->getLastMatchedShowing());
    }
}
