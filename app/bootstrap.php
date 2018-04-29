<?php
// Set a default timezone.
// This probably seems like a strange choice for a default, but it happens to be the only timezone
// that is in +11:00 all year round, and is also Australian!
// The benefit here is simply that there's no timezone conversions so the results match the inputs
// in order to avoid confusion, and match the sample in the instructions!
date_default_timezone_set('Antarctica/Macquarie');

// Set up a simple autoloader.
spl_autoload_register(function ($class_name) {
    // Convert name spaced class name to a relative file path
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';

    // Convert to an absolute path to ensure autoloading works from any entry point
    $file = __DIR__ . DIRECTORY_SEPARATOR . $file;

    if (file_exists($file) && is_readable($file)) {
        require $file;
    }

    return false;
});
