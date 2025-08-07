<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection;

use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @see https://www.php.net/manual/class.ds-collection.php
 * @template T of object
 * @template-implements IteratorAggregate<T>
 * @psalm-consistent-templates
 */
abstract class Collection implements IteratorAggregate, Countable
{
    use IndexCollection;

    /**
     * @var array<string, T>
     */
    private array $items = [];

    /**
     * Type object, get_class($item)
     *
     * @return class-string<T>
     */
    abstract public function getType(): string;

    /**
     * @param T ...$items
     */
    final public function __construct(object ...$items)
    {
        foreach ($items as $item) {
            $this->attach($item);
        }
    }

    /**
     * Adds an object in the storage.
     *
     * @param T $item The object to add.
     * @throws CollectionTypeException
     */
    final public function attach(object $item): void
    {
        if (is_a($item, $this->getType()) === false) {
            throw new CollectionTypeException(
                'The collection item must be an instance of type ' . ucfirst($this->getType())
            );
        }

        $key = $this->generateKey($item);
        $this->items[$key] ??= $item;
        $this->mapSet($this->indexBy($item), $key);
    }

    /**
     * Removes an object from the storage.
     *
     * @param T $item
     */
    final public function detach(object $item): void
    {
        $key = $this->generateKey($item);
        unset($this->items[$key]);
        $this->mapUnset($key);
    }

    /**
     * Adds all objects from another storage
     *
     * @param Collection<T> $collection
     * @throws CollectionTypeException
     */
    final public function merge(self $collection): void
    {
        foreach ($collection as $item) {
            $this->attach($item);
        }
    }

    /**
     * Returns whether the collection is empty.
     *
     * This should be equivalent to a count of zero, but is not required.
     * Implementations should define what empty means in their own context.
     *
     * @return bool whether the collection is empty.
     * @psalm-immutable
     */
    final public function isEmpty(): bool
    {
        return $this->items === [];
    }

    /**
     * Returns the number of objects in the storage.
     * @psalm-immutable
     */
    final public function count(): int
    {
        return count($this->items);
    }

    /**
     * Checks if the storage contains a specific object.
     *
     * @param T $item
     * @psalm-immutable
     */
    final public function contains(object $item): bool
    {
        return array_key_exists($this->generateKey($item), $this->items);
    }

    /**
     * Filters elements of an array using a callback function.
     *
     * @param callable(T): bool $callback
     * @return static
     * @psalm-immutable
     */
    final public function filter(callable $callback): self
    {
        return new static(
            ...array_filter($this->items, $callback)
        );
    }

    /**
     * Sort elements of an array using a callback function.
     *
     * @param callable(T, T): int $callback
     * @return static
     * @psalm-immutable
     */
    final public function sort(callable $callback): self
    {
        $items = $this->items;
        uasort($items, $callback);

        return new static(...$items);
    }

    /**
     * Returns objects by index key.
     *
     * @param string|int ...$indexKey
     * @return T|null
     * @psalm-immutable
     */
    final public function get(string | int ...$indexKey): ?object
    {
        $key = $this->mapSearch($indexKey);
        if ($key === null || array_key_exists($key, $this->items) === false) {
            return null;
        }

        return $this->items[$key];
    }

    /**
     * @return T
     * @throws CollectionOutOfRangeException
     * @psalm-immutable
     */
    final public function getFirst(): object
    {
        if ($this->items === []) {
            throw new CollectionOutOfRangeException('Collection is empty.');
        }

        return current($this->items);
    }

    /**
     * @return T
     * @throws CollectionOutOfRangeException
     * @psalm-immutable
     */
    final public function getLast(): object
    {
        if ($this->items === []) {
            throw new CollectionOutOfRangeException('Collection is empty.');
        }

        return $this->items[array_key_last($this->items)];
    }

    /**
     * @return Traversable<T>
     */
    final public function getIterator(): Traversable
    {
        return (function () {
            foreach ($this->items as $val) {
                yield $val;
            }
        })();
    }

    final public function clear(): void
    {
        $this->items = [];
        $this->mapClear();
    }

    /**
     * @return list<T>
     * @psalm-immutable
     */
    final public function toArray(): array
    {
        return array_values($this->items);
    }

    public function __debugInfo(): array
    {
        return $this->toArray();
    }

    /**
     * @param T $item
     * @return string|int|array<scalar>|null
     * @noinspection PhpMissingParamTypeInspection
     */
    protected function indexBy($item): array | int | string | null
    {
        return null;
    }

    /**
     * @param T $item
     */
    private function generateKey(object $item): string
    {
        return spl_object_hash($item);
    }
}
