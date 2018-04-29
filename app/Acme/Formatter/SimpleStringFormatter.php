<?php
namespace Acme\Formatter;

use Acme\Movie\Movie;

/**
 * Format a movie as a short string containing its name and showing time. Note that this formatter is intended
 * for use in contexts that have performed a time based search (which.. for the purpose of this app so far is always)
 * as it uses the getLastMatchedShowing() function to determine the showing time
 */
class SimpleStringFormatter implements IFormatter
{

    /**
     * @param Movie $movie
     * @return string
     */
    public function format(Movie $movie)
    {
        return sprintf(
            "%s, showing at %s\n",
            $movie->getName(),
            date("H:i", $movie->getLastMatchedShowing())
        );
    }
}
