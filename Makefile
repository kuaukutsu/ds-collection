PHP_VERSION ?= 7.4

composer:
	docker run --init -it --rm -v "$$(pwd):/app" -w /app composer:latest \
		composer update

psalm:
	docker run --init -it --rm -v "$$(pwd):/app" -e XDG_CACHE_HOME=/tmp -w /app \
		jakzal/phpqa:php${PHP_VERSION}\
		./vendor/bin/psalm

phpunit:
	docker run --init -it --rm -v "$$(pwd):/app" -u $$(id -u) -w /app \
		jakzal/phpqa:php${PHP_VERSION} \
		./vendor/bin/phpunit

phpcs:
	docker run --init -it --rm -v "$$(pwd):/app" -u $$(id -u) -w /app \
		jakzal/phpqa:php${PHP_VERSION} \
		./vendor/bin/phpcs

phpcbf:
	docker run --init -it --rm -v "$$(pwd):/app" -u $$(id -u) -w /app \
		jakzal/phpqa:php${PHP_VERSION} \
		./vendor/bin/phpcbf

rector:
	docker run --init -it --rm -v "$$(pwd):/app" -u $$(id -u) -w /app \
		jakzal/phpqa:php${PHP_VERSION} \
		./vendor/bin/rector