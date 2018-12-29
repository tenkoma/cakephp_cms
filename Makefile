all: install up
.PHONY: all

up:
	docker-compose up -d
.PHONY: up

install:
	docker-compose run composer install --ignore-platform-reqs --no-interaction
.PHONY: install

migrate:
	docker-compose run php-cli bin/cake migrations migrate
.PHONY: migrate

test:
	docker-compose run php-cli ./vendor/bin/phpunit
.PHONY: test

clean:
	docker-compose down
.PHONY: clean
