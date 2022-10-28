<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection;

use Traversable;

/**
 * @see https://www.php.net/manual/class.ds-collection.php
 * @template T of object
 */
abstract class Collection implements CollectionInterface
{
    use MapCollection;

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
    final public function __construct(...$items)
    {
        foreach ($items as $item) {
            $this->attach($item);
        }
    }

    /**
     * Adds an object in the storage
     * @param T|object $item The object to add.
     * @return void
     * @throws CollectionTypeException
     */
    final public function attach(object $item): void
    {
        if (is_a($item, $this->getType()) === false) {
            throw new CollectionTypeException(
                'The collection item must be an instance of type ' . ucfirst($this->getType())
            );
        }

        $key = spl_object_hash($item);
        $this->items[$key] ??= $item;
        $this->mapSet($this->indexBy($item), $key);
    }

    /**
     * Adds all objects from another storage
     * @param CollectionInterface $collection
     */
    final public function merge(CollectionInterface $collection): void
    {
        /** @var T $item */
        foreach ($collection as $item) {
            $this->attach($item);
        }
    }

    /**
     * Removes an object from the storage.
     * @param T|object $item
     */
    final public function detach(object $item): void
    {
        unset($this->items[spl_object_hash($item)]);
        $this->mapUnset($this->indexBy($item));
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
        return count($this) === 0;
    }

    /**
     * Returns the number of objects in the storage.
     * @return int
     */
    final public function count(): int
    {
        return count($this->items);
    }

    /**
     * Checks if the storage contains a specific object.
     * @param T|object $item
     * @return bool
     */
    final public function contains(object $item): bool
    {
        return array_key_exists(spl_object_hash($item), $this->items);
    }

    /**
     * Creates a shallow copy of the collection.
     *
     * @return static a shallow copy of the collection.
     * @psalm-immutable
     */
    final public function copy(): self
    {
        return clone $this;
    }

    /**
     * Filters elements of an array using a callback function.
     * @param callable(mixed):bool $callback
     * @return static
     * @psalm-immutable
     * @example
     * ```php
     * function(object $item): bool {
     *  return get_class($item) === $this->getType();
     * }
     * ```
     *
     */
    final public function filter(callable $callback): self
    {
        $collection = $this->copy();
        $collection->items = array_filter($this->items, $callback);

        return $collection;
    }

    /**
     * Returns objects by index key.
     * @param string|int ...$indexKey
     * @return T|object|null
     */
    final public function get(...$indexKey): ?object
    {
        if (array_key_exists($this->buildKey($indexKey), $this->map)) {
            return $this->items[$this->map[$this->buildKey($indexKey)]] ?? null;
        }

        return null;
    }

    /**
     * Removes objects from the current storage.
     */
    final public function clear(): void
    {
        $this->items = [];
    }

    /**
     * @return Traversable
     */
    final public function getIterator(): Traversable
    {
        return (function () {
            foreach ($this->items as $val) {
                yield $val;
            }
        })();
    }

    final public function toArray(): array
    {
        return array_values($this->items);
    }

    /**
     * Returns a representation that can be natively converted to JSON, which is
     * called when invoking json_encode.
     *
     * @return array
     * @psalm-immutable
     *
     * @see \JsonSerializable
     */
    final public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Invoked when calling var_dump.
     *
     * @return array
     */
    final public function __debugInfo()
    {
        return $this->toArray();
    }

    /**
     * Returns a string representation of the collection, which is invoked when
     * the collection is converted to a string.
     */
    final public function __toString()
    {
        return 'object(' . get_class($this) . ')';
    }

    /**
     * @param T|object $item
     * @return string|int|array<scalar>|null
     */
    protected function indexBy(object $item)
    {
        return null;
    }
}
