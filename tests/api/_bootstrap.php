<?php

echo "Cleaning database before tests" . PHP_EOL;
exec("APP_ENV=test; make docker-recreate-database && make docker-fill-database");