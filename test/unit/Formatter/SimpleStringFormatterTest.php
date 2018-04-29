<?php
namespace Acme\test\unit;

use Acme\Formatter\SimpleStringFormatter;
use Acme\Movie\Movie;
use PHPUnit\Framework\TestCase;

class SimpleStringFormatterTest extends TestCase
{
    function testFormatting()
    {
        $movie = new Movie();
        $movie->setName("My cool fake movie");
        $movie->setShowings(['12:00']);

        // We need to call this to populating the getLastMatchedShowing used by the formatter
        $this->assertTrue($movie->showsBetween(strtotime('11am'), strtotime('1pm')));

        $expect = "My cool fake movie, showing at 12:00\n";

        $this->assertEquals($expect, (new SimpleStringFormatter())->format($movie));
    }
}
