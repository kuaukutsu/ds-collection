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

$collectionByFiltered = $collection->filter(
    static fn(Dto $dto): bool => $dto->name === 'first'
);
```

### Сортировка

```php
$collection = new DtoCollection();
$collection->attach(new Dto(1, 'first'));
$collection->attach(new Dto(2, 'second'));
$collection->attach(new Dto(3, 'first'));
$collection->attach(new Dto(4, 'second'));

$sortCollection = $collection->sort(
    static fn(Dto $a, Dto $b): int => strcmp($a->name, $b->name)
);
```

### Индексация

В классе коллекции необходимо указать на основании какого свойства объекта индексировать коллекцию. 
Это делается при помощи метода `indexBy`, например:

```php
/**
 * @param Dto $item
 * @return int
 */
protected function indexBy($item): int
{
    return $item->id;
}
```

```php
/**
 * @param Dto $item
 * @return string
 */
protected function indexBy($item): string
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
 * @param Dto $item
 * @return array<scalar>
 */
protected function indexBy($item): array
{
    return [$item->id, $item->name];
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

```shell
docker pull ghcr.io/kuaukutsu/php:8.1-cli
```

```shell
docker pull ghcr.io/kuaukutsu/php:8.2-cli
```

Container:
- `ghcr.io/kuaukutsu/php:${PHP_VERSION}-cli` (**default**)
- `jakzal/phpqa:php${PHP_VERSION}`

shell

```shell
docker run --init -it --rm -v "$(pwd):/app" -w /app ghcr.io/kuaukutsu/php:8.1-cli sh
```

## Testing

### Unit testing

The package is tested with [PHPUnit](https://phpunit.de/). To run tests:

```shell
make phpunit
```

```shell
PHP_VERSION=7.4 make phpunit
```

### Static analysis

The code is statically analyzed with [Psalm](https://psalm.dev/). To run static analysis:

```shell
make psalm
```

```shell
PHP_VERSION=7.4 make psalm
```

### Code Sniffer

```shell
make phpcs
```

### Rector

```shell
make rector
```
