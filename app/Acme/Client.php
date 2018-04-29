<?php
namespace  Acme;

use Acme\Movie\Movie;
use Acme\Movie\MovieFactory;

/**
 * Class for interacting with the acme movies API
 */
class Client
{
    /**
     * Constant defining an API URL
     *
     * @note In a real production app this would likely be stored in some kind of environment specific config
     *       however doing so here did not feel necessary
     */
    const API_URI = 'https://pastebin.com/raw/cVyp3McN';

    /**
     * The time offset to be applied to the provided time when fetching recommendations
     * the system will only recommend movies that have a showing at least this many seconds
     * after the input time
     **/
    const TIME_OFFSET_SECONDS = 1800;

    /**
     * @param string          $genre The genre that must be present on the movie to be recommended
     * @param string|null $time  Time input in any format understood by strtotime
     *                               Movies will be recommended if they have a screening atleast 30 minutes after this
     * @return Movie[]
     * @throws \Exception
     */
    public function getRecommendations(string $genre, string $time = null)
    {
        // Default to current time if none has been supplied
        if (!$time) {
            $time = time();

        // Otherwise convert and validate
        } else {
            $time = strtotime($time);
            if (!$time) {
                throw new \Exception('Time is not in a recognisable format');
            }
        }

        // append desired offset to the provided time; presumedly representing] preparation and travel time
        $time += self::TIME_OFFSET_SECONDS;

        // Fetch everything
        $movies = $this->getAllMovies();

        // Filter
        $movies = $this->filterMoviesByGenre($movies, $genre);
        $movies = $this->filterMoviesByTime($movies, $time, null);

        // Sort
        $movies = $this->sortMoviesByRating($movies);

        return $movies;
    }

    /**
     * Function to return all available movies from the API
     *
     * @return Movie[]
     * @throws \Exception
     */
    public function getAllMovies()
    {
        $response = $this->callApi();

        return MovieFactory::createFromApiResponse($response);
    }

    /**
     * Function to sort a provided array of movies by rating in desc order
     *
     * @param Movie[] $movies
     * @return Movie[]
     */
    protected function sortMoviesByRating(array $movies)
    {
        usort(
            $movies,
            function (Movie $a, Movie $b) {
                return $b->getRating() - $a->getRating();
            }
        );

        return $movies;
    }

    /**
     * Filter a provided array of movies and return a new array containing only those that match the requested genre
     *
     * @param Movie[] $movies
     * @param String $genre
     * @return Movie[]
     */
    protected function filterMoviesByGenre(array $movies, string $genre)
    {
        return array_filter(
            $movies,
            function(Movie $movie) use ($genre) {
                return $movie->hasGenre($genre);
            }
        );
    }

    /**
     * Filter a provided array of movies and return only those that have a showing within the provided
     * start and end time frame.  If start or end are not provided it will be an open ended search
     *
     * @param Movie[] $movies
     * @param String|null $start If provided, the start of the time window we want screenings to fall within
     * @param String|null $end   If provided, the end of the time window we want screenings to fall within
     * @return Movie[]
     */
    protected function filterMoviesByTime(array $movies, string $start = null, string $end = null)
    {
        return array_filter(
            $movies,
            function(Movie $movie) use ($start, $end) {
                return $movie->showsBetween($start, $end);
            }
        );
    }

    /**
     * Make an API call and return a json decoded response.
     *
     * In most real apps this would probably include some kind of pagination or other parameters
     * that get passed to the API, and quite possibly some form of response caching
     * ... but here we just have a small 1 pager so lets not go overboard
     *
     * In an ideal world we would also use some library or abstraction layer for curl to allow for improved
     * testability, but with 1 simple call we can do without that here for now
     */
    protected function callApi()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,            static::API_URI);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_TIMEOUT,        5);

        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($status != 200 || !$response || !json_decode($response)) {
            throw new \Exception('Invalid or empty response received from API');
        }

        return $response;
    }

}
