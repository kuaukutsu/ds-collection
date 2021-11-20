# Data structure Collection

```php
$collection = new DtoCollection();
$collection->attach(new Dto(1, 'first'));
$collection->attach(new Dto(2, 'second'));

$collectionOther = new DtoCollection();
$collectionOther->attach(new Dto(3, 'third'));
$collectionOther->attach(new Dto(4, 'fourth'));

$collection->merge($collectionOther);

$collection->filter(static function (Dto $dto): bool {
    return $dto->id > 2; 
})

```

## Docker

local

```shell
docker build -t kuaukutsu/ds-collection:php .
docker run --init -it --rm -v "$(pwd):/app" -w /app kuaukutsu/ds-collection:php sh
```

## Testing

### Unit testing

The package is tested with [PHPUnit](https://phpunit.de/). To run tests:

```shell
./vendor/bin/phpunit
```

local

```shell
docker run --init -it --rm -v "$(pwd):/app" -w /app kuaukutsu/ds-collection:php ./vendor/bin/phpunit 
```

### Code Sniffer

local

```shell
docker run --init -it --rm -v "$(pwd):/app" -w /app kuaukutsu/ds-collection:php ./vendor/bin/phpcs 
```

phpqa

```shell
docker run --init -it --rm -v "$(pwd):/app" -v "$(pwd)/phpqa/tmp:/tmp" -w /app jakzal/phpqa phpcs
```

### Static analysis

The code is statically analyzed with [Psalm](https://psalm.dev/). To run static analysis:

```shell
./vendor/bin/psalm
```

local

```shell
docker run --init -it --rm -v "$(pwd):/app" -w /app kuaukutsu/ds-collection:php ./vendor/bin/psalm 
```

phpqa

```shell
docker run --init -it --rm -v "$(pwd):/app" -v "$(pwd)/phpqa/tmp:/tmp" -w /app jakzal/phpqa psalm
```
