<?php
namespace Acme\Movie;

/**
 * Class representing an individual movie result from the ACME API
 *
 * @package Acme
 */
class Movie {

    /**
     * Constants defining the minimum and maximum valid rating
     */
    const MIN_RATING = 0;
    const MAX_RATING = 100;

    /**
     * @var String The name of the movie
     */
    protected $name;

    /**
     * @var int A numeric rating out of 100
     */
    protected $rating;

    /**
     * @var String[] A list of genres that apply to the movie
     */
    protected $genres;

    /**
     * @var int[] A list of times at which the movie is playing in unix timestamps
     */
    protected $showings;

    /**
     * @var int
     */
    protected $lastMatchedShowing;

    /**
     * Set the name of the movie
     *
     * @param String $name
     * @return $this
     */
    public function setName(string $name) {
        $this->name = $name;

        return $this;
    }

    /**
     * @param int $rating Set the rating of the movie
     * @return $this
     * @throws \Exception
     * @note Based on the sample values provided I have made the assumption
     *       that rating is between 0 and 100 and validated as such
     * @note We're not using strict typing here, numeric strings are accepted but cast to int
     */
    public function setRating(int $rating)
    {
        // Rating must be in the supported range
        if ($rating < self::MIN_RATING || $rating > self::MAX_RATING) {
            throw new \Exception("Invalid rating supplied");
        }

        $this->rating = $rating;

        return $this;
    }

    /**
     * @param String[] $genres an array of genres
     * @return $this
     * @throws \Exception
     */
    public function setGenres(array $genres)
    {
        $filtered = array_filter(
            $genres,
            function($genre) {
                return is_string($genre);
            }
        );

        if (count($filtered) !== count($genres)) {
            throw new \Exception("All genres must be strings");
        }

        $this->genres = $genres;

        return $this;
    }

    /**
     * @param array $showings An array of show times as strings.
     *                        Any format that can be understood by strtotime is accepted
     * @return $this
     * @throws \Exception
     *
     */
    public function setShowings(array $showings)
    {
        // Show times are provided as strings, and the must be recognisable by strtotime
        // although we are not concerned with the exact format
        $filtered = array_filter(
            $showings,
            function($showing) {
                return is_string($showing) && strtotime($showing);
            }
        );

        if (count($filtered) !== count($showings)) {
            throw new \Exception("An unrecognisable showing time was provided");
        }

        // Store show times internally as unix timestamps since they tend to be easier to work with
        $showings = array_map(
            function($showing) {
                return strtotime($showing);
            },
            $showings
        );

        // Sort showings by time, in case they're provided out of order
        sort($showings);

        $this->showings = $showings;

        return $this;
    }

    /**
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @return String[]
     */
    public function getGenres()
    {
        return $this->genres;
    }

    /**
     * @param $genre
     * @return bool
     */
    public function hasGenre($genre)
    {
        return in_array($genre, $this->genres);
    }

    /**
     * @param string $time_format
     * @return int[] Returns all available show times
     */
    public function getShowings($time_format = "H:i")
    {
        return array_map(
            function($showing) use ($time_format) {
                return date($time_format, $showing);
            },
            $this->showings
        );
    }

    /**
     * Determine if the movie has a showing in the specified time window.
     *
     * Both start and end are optional in order to support open ended searches
     * whichever options are specified should be provided as unix timestamps
     *
     * @param null|int $start
     * @param null|int $end
     * @return bool
     * @throws \Exception
     */
    public function showsBetween(int $start = null, int $end = null)
    {
        // Default start and end to outer limits to simplify searching logic
        $start = $start ?: 0;
        $end   = $end   ?: PHP_INT_MAX;

        // Check each showing to see if it's within the requested range
        if (!empty($this->showings)) {
            foreach ($this->showings as $showing) {
                if ($showing >= $start && $showing <= $end) {
                    $this->lastMatchedShowing = $showing;

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * This is a convenience function that allows us to easily fetch the time
     * of the showing that was matched by the previous call to showsBetween()
     *
     * This provides an easy and efficient way to perform a time based search in one part of the app,
     * and display the correct showing time at some later point
     */
    public function getLastMatchedShowing()
    {
        if ($this->lastMatchedShowing) {
            return $this->lastMatchedShowing;
        }

        return null;
    }
}
