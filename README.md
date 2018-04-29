Basic Assumptions 
=================
- The instructions state `only return recommendations that have a showing time 30 minutes ahead of the input time`. I
 have assumed this is intended to mean `at least 30 minutes ahead`
- It is assumed that the API returns data for a single days movie times and that it is for the current day

Requirements
============
- PHP 7.0+
- PHPUnit 7.1.x for running tests (PHAR included for convenience)

Installation
============
Simply check out the repo to any location covered by PHP's open_basedir and you're good to go

CLI App
=======
`bin/acme.php` contains a CLI app for the recommendation system.

- Genre can be specified via either `-g` or `--genre` parameters 
- Time can be specified via either `-t` or `--time` parameters

**Sample Usage:**

    php bin/acme.php --genre Comedy --time 12:00


Web App
=======
A simple web app has also been provided that can be launched with PHPs build in web server.

- Navigate to the public directory
- run `php -S localhost:8000`
- Open [http://localhost:8000/](http://localhost:8000/)


Tests
=====
The required phpunit phar has been included for convenience.
Simply navigate to the `test` directory and run `./phpunit.phar`
