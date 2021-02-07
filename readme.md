# Documentation

## How to run:

`docker-compose up -d --build` - to build environment and run the application

`docker-compose exec app composer install` - to install all needed dependencies

`docker-compose exec app ./vendor/bin/phpunit tests/` - to run tests

## Dependencies:
I only used `Guzzle` for performing HTTP requests to SM-APi and `PHPUnit` for testing the code.
But for real-word applications it is worth looking at popular PHP libraries.

## Api routes:
GET `/posts/reports/avg-length-per-month` - Average character length of posts per month

GET `/posts/reports/the-longest-per-month` - Longest post by character length per month

GET `/posts/reports/total-by-week` - Total posts split by week number

GET `/posts/reports/avg-count-per-user-per-month` - Average number of posts per user per month
