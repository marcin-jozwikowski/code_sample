install:
	cp docker-compose.yml.dist docker-compose.yml
	docker-compose up -d

uninstall:
	docker-compose down --rmi=all --remove-orphans

init:
	docker exec -t -i -u application sample-web bash -c "composer install"
	docker exec -t -i -u application sample-web bash -c "make docker-recreate-database"
	docker exec -t -i -u application sample-web bash -c "APP_ENV=dev && make docker-fill-database"

test:
	docker exec -t -i -u application sample-web bash -c "make docker-test"

start:
	docker-compose start

stop:
	docker-compose stop

docker-recreate-database:
	bin/console d:d:d --force --if-exists && \
	bin/console d:d:c && \
	bin/console d:m:m -n

docker-fill-database:
	bin/console d:f:l -n

docker-test:
	vendor/bin/phpstan analyse ./src && vendor/bin/codecept run