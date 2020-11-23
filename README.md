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

## Testing

### Unit testing

The package is tested with [PHPUnit](https://phpunit.de/). To run tests:

```shell
./vendor/bin/phpunit
```

local
```shell
docker run --init -it --rm -v "$(pwd):/project" -v "$(pwd)/phpqa/tmp:/tmp" -w /project jakzal/phpqa php -d pcov.enabled=1 /tools/phpunit --coverage-clover=coverage.clover --colors=always
```

### Mutation testing

The package tests are checked with [Infection](https://infection.github.io/) mutation framework. To run it:

```shell
./vendor/bin/infection
```

local
```shell
docker run --init -it --rm -v "$(pwd):/project" -v "$(pwd)/phpqa/tmp:/tmp" -w /project jakzal/phpqa /tools/infection run --initial-tests-php-options='-dpcov.enabled=1'
```

or
```shell
docker run --init -it --rm -v "$(pwd):/project" -v "$(pwd)/phpqa/tmp:/tmp" -w /project jakzal/phpqa ./vendor/bin/roave-infection-static-analysis-plugin run --initial-tests-php-options='-dpcov.enabled=1'
```

### Static analysis

The code is statically analyzed with [Psalm](https://psalm.dev/). To run static analysis:

```shell
./vendor/bin/psalm
```
