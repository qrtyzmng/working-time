PHP_RUN = docker-compose run --rm --no-deps

.PHONY: setup
setup:
	docker-compose build
	make up
	make vendor

.PHONY: up
up:
	-docker network create working-time
	docker-compose up -d

.PHONY: vendor
vendor:
	$(PHP_RUN) php-fpm /usr/bin/composer install

.PHONY: attach
attach:
	docker-compose exec php-fpm bash

.PHONY: down
down:
	docker-compose down --remove-orphans
