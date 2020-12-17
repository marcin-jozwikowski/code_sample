# Marcin Jóźwikowski - a code sample

## API

Visit [sample.loc](http://sample.loc) or [127.0.0.1](http://127.0.0.1) to see OpenAPI documentation.
The address can be changed in docker-compose file.

## Running

To run the app you need docker with docker-compose.

* Run `make install` to create the container.
* Run `make init` to install dependencies.
* Run `make test` to run PhpStan and Codeception tests.
* Run `make start` to run the app container.
* Run `make stop` to stop the container.

To remove the created container run `make uninstall`
