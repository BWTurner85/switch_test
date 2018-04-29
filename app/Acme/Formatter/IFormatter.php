<?php
namespace Acme\Formatter;

use Acme\Movie\Movie;

/**
 * Interface to define the contract for a class to format a Movie in some way
 */
interface IFormatter
{
    public function format(Movie $movie);
}
