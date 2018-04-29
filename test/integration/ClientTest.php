<?php
namespace Acme\test\integration;

use Acme\Client;
use Acme\Movie\Movie;
use PHPUnit\Framework\TestCase;

require_once 'stub/FixtureInjectingClientStub.php';
require_once 'stub/FailingApiUrlClientStub.php';

class ClientTest extends TestCase
{

    public function clientTestProvider()
    {
        return [
            // A few samples that are expected to return nothing first
            [ 'Drama',   '21:00', [ ] ],
            [ 'Romance', '00:00', [ ] ],
            [ 'Romance',  null,   [ ] ],

            // Single item result
            [
                'Drama',
                '18:00',
                [
                    [
                        'name' => 'Moonlight',
                        'rating' => 98,
                        'genres' => [ 'Drama' ],
                        'showings' => [ '18:30', '20:30' ],
                        'lastMatchedShowing' => strtotime('18:30:00+11:00')
                    ]
                ]
            ],

            // Multi item result
            [
                'Comedy',
                '12:00',
                [
                    [
                        'name' => 'Zootopia',
                        'rating' => 92,
                        'genres' => [ 'Action & Adventure', 'Animation', 'Comedy' ],
                        'showings' => [ '19:10', '21:00' ],
                        'lastMatchedShowing' => strtotime('19:10')
                    ],
                    [
                        'name' => 'Shaun The Sheep',
                        'rating' => 80,
                        'genres' => [ 'Animation', 'Comedy' ],
                        'showings' => [ '19:00' ],
                        'lastMatchedShowing' => strtotime('19:00')
                    ],

                ]
            ],

        ];
    }

    /**
     * Test with client stub that injects static fixtures and assert on results
     *
     * @param string $genre
     * @param string|null $time
     * @param array $expectation  Contains an associative array of values to be checked against the recieved result
     * @dataProvider clientTestProvider
     */
    public function testClientFunctionalityWithStaticFixture($genre, $time, $expectation)
    {
        // Create client stub and inject our fixture response
        $client = new FixtureInjectingClientStub();
        $client->setApiResponse(file_get_contents(__DIR__ . '/../fixtures/ValidResponse.json'));

        // Grab results
        $movies = $client->getRecommendations($genre, $time);

        // Perform our assertions
        $this->assertEquals(count($expectation), count($movies));
        foreach ($expectation as $key=>$expected_movie) {
            $actual_movie = $movies[$key];

            $this->assertEquals($expected_movie['name'], $actual_movie->getName(), "Name incorrect for key $key");
            $this->assertEquals($expected_movie['rating'], $actual_movie->getRating(), "Rating incorrect for key $key");
            $this->assertEquals($expected_movie['genres'], $actual_movie->getGenres(), "Genres incorrect for key $key");
            $this->assertEquals($expected_movie['showings'], $actual_movie->getShowings(), "Showings incorrect for key $key");
            $this->assertEquals($expected_movie['lastMatchedShowing'], $actual_movie->getLastMatchedShowing(), "lastMatchedShowing incorrect for key $key");
        }
    }

    /**
     * Test with client stub that forces API call failure
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Invalid or empty response received from API
     */
    public function testExceptionThrownWhenApiCallFails()
    {
        (new FailingApiUrlClientStub())->getRecommendations('Drama', '12:00');

    }

    /**
     * This test makes live API calls and performs basic assertions on the result.
     *php
     * We use a common genre, and the earliest possible time to maximise the chance of actually
     * getting a result to do assertions on.
     *
     * This test is subject to false negatives if no movie of the requested genre is available
     * or there are problems with the downstream APi
     */
    public function testWithLiveApiCall()
    {
        $movies = (new Client())->getRecommendations('Comedy', '00:00');

        $this->assertGreaterThan(0, count($movies));
        $this->assertContainsOnlyInstancesOf(Movie::class, $movies);
        foreach ($movies as $movie) {
            $this->assertNotEmpty($movie->getLastMatchedShowing());
        }
    }
}
