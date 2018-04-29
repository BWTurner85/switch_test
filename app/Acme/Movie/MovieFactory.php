<?php
namespace Acme\Movie;

use Acme\Util\Logger;

/**
 * Factory class for creating movie objects
 */
class MovieFactory
{
    /**
     * Function to create a movie out of an individual response item from the API
     *
     * A sample input item might look like the below
     * [
     *    "name"     => "Moonlight",
     *    "rating"   => 98,
     *    "genres"   => [ "Drama" ],
     *    "showings" => [ "18:30:00+11:00", "20:30:00+11:00" ]
     * ]
     *
     * @param array $item
     * @return Movie
     * @throws \Exception
     */
    public static function createFromApiItem(array $item)
    {
        // Name, Rating, Genres and showings are all required fields
        $required_keys = [ 'name', 'rating', 'genres', 'showings' ];
        foreach ($required_keys as $key) {
            if (!array_key_exists($key, $item)) {
                throw new \Exception("Required key $key does not exist in api item");
            }
        }

        return (new Movie())
            ->setName($item['name'])
            ->setRating($item['rating'])
            ->setGenres($item['genres'])
            ->setShowings($item['showings']);
    }

    /**
     * Create an array of movies based on the response from an API
     *
     * @param string $response A raw text response from the API
     * @return Movie[]
     * @see createFromApiItem
     */
    public static function createFromApiResponse(string $response)
    {
        $items = json_decode($response, true);
        $movies = [];
        foreach ($items as $response_item) {
            try {
                $movies[] = self::createFromApiItem($response_item);
            } catch (\Exception $e) {
                Logger::log("Skipping invalid response item: " . $e->getMessage());
            }
        }

        return $movies;
    }
}
