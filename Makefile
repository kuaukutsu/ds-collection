PHP_VERSION ?= 7.4

composer:
	docker run --init -it --rm -v "$$(pwd):/app" -u $$(id -u) -w /app composer:latest \
		composer update

psalm:
	docker run --init -it --rm -v "$$(pwd):/app" -v "$$(pwd)/phpqa/tmp:/tmp" -w /app \
		jakzal/phpqa:php${PHP_VERSION}\
		./vendor/bin/psalm --no-cache

phpunit:
	docker run --init -it --rm -v "$$(pwd):/app" -u $$(id -u) -w /app \
		jakzal/phpqa:php${PHP_VERSION} \
		./vendor/bin/phpunit

phpcs:
	docker run --init -it --rm -v "$$(pwd):/app" -u $$(id -u) -w /app \
		jakzal/phpqa:php${PHP_VERSION} \
		./vendor/bin/phpcs

rector:
	docker run --init -it --rm -v "$$(pwd):/app" -u $$(id -u) -w /app \
		jakzal/phpqa:php${PHP_VERSION} \
		./vendor/bin/rector
