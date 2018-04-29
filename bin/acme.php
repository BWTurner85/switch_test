<?php
use Acme\Cli\OptionsManager;
use Acme\Client as AcmeClient;
use Acme\Formatter\SimpleStringFormatter;

require_once __DIR__ . '/../app/bootstrap.php';

// Grab command line arguments
$options = getopt(OptionsManager::SHORT_OPTS, OptionsManager::LONG_OPTS);

// Validate them, display help if anything fails
try {
    $option_manager = new OptionsManager($options);
    $option_manager->validate();
} catch (Exception $e) {
    help($e);
    exit(1);
}

// Fetch recommendations
try {
    $movies = (new AcmeClient())->getRecommendations(
        $option_manager->getGenre(),
        $option_manager->getTime()
    );
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage() . "\n";
    exit(1);
}

// No movies returned? what a shame.. let the user know
if (empty($movies)) {
    echo "no movie recommendations\n";
    exit(0);
}

// Otherwise format them nicely and we're done
$formatter = new SimpleStringFormatter();
foreach ($movies as $movie) {
    echo $formatter->format($movie);
}

function help(Exception $exception)
{
    echo "Error: " . $exception->getMessage() . "\n";
    echo "Usage: php [options] " . basename(__FILE__) . "\n";
    echo "Options: \n";
    echo "\t-g GENRE, --genre=GENRE  [REQUIRED]\n\t\tThe genre that recommmended movies must match\n";
    echo "\t-t TIME, --time=TIME  [OPTIONAL: defaults to current time]\n\t\tThe time from which to recommend movies. Movies beginning more than 30 min after this time will be recommended\n";
}
