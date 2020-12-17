install:
	cp docker-compose.yml.dist docker-compose.yml
	docker-compose up -d

uninstall:
	docker-compose down --rmi=all --remove-orphans

init:
	docker exec -t -i -u application sample-web bash -c "make docker-init"

test:
	docker exec -t -i -u application sample-web bash -c "make docker-test"

start:
	docker-compose start

stop:
	docker-compose stop

docker-init:
	composer install && \
	bin/console d:d:d --force --if-exists && \
	bin/console d:d:c && \
	bin/console d:m:m -n && \
	bin/console d:f:l -n

docker-test:
	vendor/bin/phpstan analyse ./src && vendor/bin/codecept run