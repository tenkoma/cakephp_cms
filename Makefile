all: install up
.PHONY: all

up:
	docker-compose up -d
.PHONY: up

install:
	docker-compose run composer install --ignore-platform-reqs --no-interaction
.PHONY: install

clean:
	docker-compose down
.PHONY: clean
