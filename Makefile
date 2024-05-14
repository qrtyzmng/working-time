PHP_RUN = docker-compose run --rm --no-deps
PHP_EXEC = docker-compose exec php-fpm bash -c

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

.PHONY: test
test:
	$(PHP_EXEC) 'XDEBUG_MODE=off vendor/bin/phpunit \
 			--coverage-filter src tests \
 			--colors \
 			--testdox \
 			--log-junit=var/log/coverage/junit.xml \
 			--coverage-xml=var/log/coverage \
 			--coverage-html=var/coverage \
 	'

.PHONY: codestyle-fix
codestyle-fix:
	$(PHP_EXEC) 'vendor/bin/php-cs-fixer fix src tests \
			--config=dev/tools/php_cs.php \
			--cache-file=dev/tools/php_cs.cache \
			--path-mode=intersection \
			--allow-risky=yes \
			--ansi \
	'

.PHONY: static-analysis
static-analysis:
	$(PHP_EXEC) 'vendor/bin/phpstan \
			-cdev/tools/phpstan.neon \
			analyse \
			src tests \
	'
