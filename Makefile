PHP_VERSION ?= 8.1
USER = $$(id -u)

# https://marmelab.com/blog/2016/02/29/auto-documented-makefile.html
.PHONY: help
.DEFAULT_GOAL := help

help: ## Display this help screen
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

check: ## detect violations of a defined coding standard and run tests
	docker run --init -it --rm -u ${USER} -v "$$(pwd):/app" -w /app \
		composer:latest \
		composer check

check-83:
	-PHP_VERSION=8.3 make phpcs
	-PHP_VERSION=8.3 make psalm
	-PHP_VERSION=8.3 make phpstan
	-PHP_VERSION=8.3 make phpunit

check-84:
	-PHP_VERSION=8.4 make phpcs
	-PHP_VERSION=8.4 make psalm
	-PHP_VERSION=8.4 make phpstan
	-PHP_VERSION=8.4 make phpunit

auto-repair: ## automatically correct
	-make phpcbf
	-make rector

composer: ## composer install
	docker run --init -it --rm -u ${USER} -v "$$(pwd):/app" -w /app \
		composer:latest \
		composer install --optimize-autoloader --ignore-platform-reqs

composer-up: ## composer update
	docker run --init -it --rm -u ${USER} -v "$$(pwd):/app" -w /app \
		composer:latest \
		composer update --no-cache --ignore-platform-reqs

composer-dump: ## composer dump-autoload
	docker run --init -it --rm -u ${USER} -v "$$(pwd):/app" -w /app \
		composer:latest \
		composer dump-autoload

composer-cli: ## composer console
	docker run --init -it --rm -u ${USER} -v "$$(pwd):/app" -w /app \
		composer:latest \
		sh

psalm: ## psalm
	docker run --init -it --rm -v "$$(pwd):/app" -u ${USER} -w /app \
		ghcr.io/kuaukutsu/php:${PHP_VERSION}-cli \
		./vendor/bin/psalm --php-version=${PHP_VERSION} --no-cache

phpstan: ## phpstan
	docker run --init -it --rm -v "$$(pwd):/app" -u ${USER} -w /app \
		ghcr.io/kuaukutsu/php:${PHP_VERSION}-cli \
		./vendor/bin/phpstan analyse -c phpstan.neon

phpunit: ## phpunit
	docker run --init -it --rm -v "$$(pwd):/app" -u ${USER} -w /app \
		ghcr.io/kuaukutsu/php:${PHP_VERSION}-cli \
		./vendor/bin/phpunit

phpcs: ## php code sniffer
	docker run --init -it --rm -v "$$(pwd):/app" -u ${USER} -w /app \
		ghcr.io/kuaukutsu/php:${PHP_VERSION}-cli \
		./vendor/bin/phpcs

phpcbf: ## php code beautifier and fixer
	docker run --init -it --rm -v "$$(pwd):/app" -u ${USER} -w /app \
		ghcr.io/kuaukutsu/php:${PHP_VERSION}-cli \
		./vendor/bin/phpcbf

rector: ## rector
	docker run --init -it --rm -v "$$(pwd):/app" -u ${USER} -w /app \
		ghcr.io/kuaukutsu/php:${PHP_VERSION}-cli \
		./vendor/bin/rector
