<?php
namespace Acme\test\unit;

use Acme\Movie\Movie;
use Acme\Movie\MovieFactory;
use PHPUnit\Framework\TestCase;

class MovieFactoryTest extends TestCase
{
    public function testCreateFromValidApiResponse()
    {
        $json = file_get_contents(__DIR__ . '/../../fixtures/ValidResponse.json');

        $movies = MovieFactory::createFromApiResponse($json);

        $this->assertCount(4, $movies);
        $this->assertContainsOnlyInstancesOf(Movie::class, $movies);
    }

    public function testCreateFromApiResponseWithInvalidItems()
    {
        $json = file_get_contents(__DIR__ . '/../../fixtures/ResponseWithInvalidItems.json');

        $movies = MovieFactory::createFromApiResponse($json);

        $this->assertCount(2, $movies);
        $this->assertContainsOnlyInstancesOf(Movie::class, $movies);
    }

}
