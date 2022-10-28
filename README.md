# Data structure Collection

Коллекция объектов.

### Примеры

```php
$collection = new DtoCollection();
$collection->attach(new Dto(1, 'first'));
$collection->attach(new Dto(2, 'second'));

$collectionOther = new DtoCollection();
$collectionOther->attach(new Dto(3, 'third'));
$collectionOther->attach(new Dto(4, 'fourth'));

$collection->merge($collectionOther);
```

### Фильтрация

```php
$collection = new DtoCollection();
$collection->attach(new Dto(1, 'first'));
$collection->attach(new Dto(2, 'second'));
$collection->attach(new Dto(3, 'first'));
$collection->attach(new Dto(4, 'second'));

$collectionByFiltered = $collection->filter(static function (Dto $dto): bool {
    return $dto->name === 'first'; 
});
```

### Индексация

В классе коллекции необходимо указать на основании какого свойства объекта индексировать коллекцию. 
Это делается при помощи метода `indexBy`, например:

```php
/**
 * @param Dto $item
 * @return int
 */
protected function indexBy(object $item): int
{
    return $item->id;
}
```

```php
/**
 * @param Dto $item
 * @return string
 */
protected function indexBy(object $item): string
{
    return $item->name;
}
```

Это позволяет получить быстрый доступ к объекту по ключу индекса, например для indexBy по ключу name:

```php
$collection = new DtoCollection();
$collection->attach(new Dto(1, 'first'));
$collection->attach(new Dto(2, 'second'));

$dto = $collection->get('second');
```

#### Составные ключи

Ключ индексирования может быть составным, например:

```php
/**
 * @param Dto|object $item
 * @return array<scalar>
 */
protected function indexBy(object $item): array
{
    return [(int)$item->id, (string)$item->name];
}
```

```php
$collection = new DtoCollection();
$collection->attach(new Dto(1, 'first'));
$collection->attach(new Dto(2, 'second'));
$collection->attach(new Dto(3, 'third'));

$dto = $collection->get(2, 'second');
```

## Feature

- php >= 8.0
- **WeakMap** (https://www.php.net/manual/ru/class.weakmap.php, https://sergeymukhin.com/blog/php-8-weakmaps-slabye-karty)

## Docker

local

```shell
docker build -t kuaukutsu/ds-collection .
docker run --init -it --rm -v "$(pwd):/app" -w /app kuaukutsu/ds-collection sh
```

## Testing

### Unit testing

The package is tested with [PHPUnit](https://phpunit.de/). To run tests:

```shell
./vendor/bin/phpunit
```

local

```shell
docker run --init -it --rm -v "$(pwd):/app" -w /app kuaukutsu/ds-collection ./vendor/bin/phpunit 
```

### Code Sniffer

local

```shell
docker run --init -it --rm -v "$(pwd):/app" -w /app kuaukutsu/ds-collection ./vendor/bin/phpcs 
```

phpqa

```shell
docker run --init -it --rm -v "$(pwd):/app" -v "$(pwd)/phpqa/tmp:/tmp" -w /app jakzal/phpqa:php7.4 phpcs
```

### Static analysis

The code is statically analyzed with [Psalm](https://psalm.dev/). To run static analysis:

```shell
./vendor/bin/psalm
```

local

```shell
docker run --init -it --rm -v "$(pwd):/app" -w /app kuaukutsu/ds-collection ./vendor/bin/psalm 
```

phpqa

```shell
docker run --init -it --rm -v "$(pwd):/app" -v "$(pwd)/phpqa/tmp:/tmp" -w /app jakzal/phpqa:php7.4 psalm
```
