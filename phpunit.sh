#!/bin/bash

docker-compose run --rm phpunit phpunit --no-configuration --bootstrap ./include_test.php ./tests-legacy/test.php 
