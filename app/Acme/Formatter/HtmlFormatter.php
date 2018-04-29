<?php
namespace Acme\Formatter;

use Acme\Movie\Movie;

/**
 * Format a movie as a short string containing its name and showing time. Note that this formatter is intended
 * for use in contexts that have performed a time based search (which.. for the purpose of this app so far is always)
 * as it uses the getLastMatchedShowing() function to determine the showing time
 */
class HtmlFormatter implements IFormatter
{

    /**
     * @param Movie $movie
     * @return string
     */
    public function format(Movie $movie)
    {
        return sprintf(
            "<div class='movie'>
                <span class='movie-attr title'>
                    <span class='label'>Title: </span> 
                    <span class='value'>%s</span>
                </span>
                <span class='movie-attr rating'>
                    <span class='label'>Rating: </span> 
                    <span class='value'>%d</span>
                </span>
                <span class='movie-attr genres'>
                    <span class='label'>Genres: </span> 
                    <span class='value'>%s</span>
                 </span>
                <span class='movie-attr showing'>
                    <span class='label'>Showing at: </span> 
                    <span class='value'>%s</span>
                </span>
            </div>",
            $movie->getName(),
            $movie->getRating(),
            implode(',  ', $movie->getGenres()),
            date("g:ia", $movie->getLastMatchedShowing())
        );
    }
}
