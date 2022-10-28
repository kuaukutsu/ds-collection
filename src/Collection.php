<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection;

use Traversable;
use Ds\Traits\GenericCollection;

/**
 * @see https://www.php.net/manual/class.ds-collection.php
 * @template T of object
 * @psalm-suppress MissingImmutableAnnotation
 */
abstract class Collection implements CollectionInterface
{
    use GenericCollection;

    /**
     * @var array<string, T>
     */
    private array $items = [];

    /**
     * @var array<string, string>
     */
    private array $map = [];

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
     * Checks if the storage contains a specific object.
     * @param T|object $item
     * @return bool
     */
    final public function contains(object $item): bool
    {
        return array_key_exists(spl_object_hash($item), $this->items);
    }

    /**
     * Filters elements of an array using a callback function.
     * @param callable(mixed):bool $callback
     * @return static
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
        $collection = clone $this;
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
     * Returns the number of objects in the storage.
     * @return int
     */
    final public function count(): int
    {
        return count($this->items);
    }

    /**
     * Removes objects from the current storage.
     */
    final public function clear(): void
    {
        $this->items = [];
    }

    /**
     * @return Traversable<mixed, mixed>
     */
    final public function getIterator(): Traversable
    {
        return (function () {
            foreach ($this->items as $key => $val) {
                yield $key => $val;
            }
        })();
    }

    final public function toArray(): array
    {
        return array_values($this->items);
    }

    /**
     * @param T|object $item
     * @return string|int|array<scalar>|null
     */
    protected function indexBy(object $item)
    {
        return null;
    }

    /**
     * @param string|int|array<scalar>|null $index
     * @param string $key
     */
    private function mapSet($index, string $key): void
    {
        if (empty($index) === false) {
            $this->map[$this->buildKey($index)] = $key;
        }
    }

    /**
     * @param string|int|array<scalar>|null $index
     */
    private function mapUnset($index): void
    {
        if (empty($index) === false) {
            unset($this->map[$this->buildKey($index)]);
        }
    }

    /**
     * @param string|int|array<scalar> $index
     * @return string
     */
    private function buildKey($index): string
    {
        if (is_array($index)) {
            $index = implode(':', $index);
        }

        if (is_numeric($index)) {
            $index = (string)$index;
        }

        return ctype_alnum($index) && mb_strlen($index, '8bit') <= 32 ? $index : md5($index);
    }
}
