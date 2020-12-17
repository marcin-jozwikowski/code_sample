# Marcin Jóźwikowski - a code sample

### Running

To run the app you need docker with docker-compose.

* Run `make install` to create the container.
* Run `make init` to install dependencies.
* Run `make test` to run PhpStan and Codeception tests.
* Run `make start` to run the app container. The default address is [sample.loc](http://sample.loc) but [127.0.0.1](http://127.0.0.1) will also work. The address it can be changed in docker-compose file.
* Run `make stop` to stop the container.

To remove the created container run `make uninstall`
