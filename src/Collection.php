<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection;

use ArrayIterator;
use Traversable;
use Ds\Traits\GenericCollection;

/**
 * Class Collection
 *
 * @see https://www.php.net/manual/class.ds-collection.php
 * @template T of object
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
     * @param string|int $indexKey
     * @return T|object|null
     */
    final public function get($indexKey): ?object
    {
        $key = $this->map[$this->buildKey((string)$indexKey)] ?? null;
        if ($key === null) {
            return null;
        }

        return $this->items[$key] ?? null;
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
     * @return Traversable
     */
    final public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    final public function toArray(): array
    {
        return array_values($this->items);
    }

    /**
     * @param T|object $item
     * @return string|int|null
     */
    protected function indexBy(object $item)
    {
        return null;
    }

    /**
     * @param string|int|null $index
     * @param string $key
     */
    private function mapSet($index, string $key): void
    {
        if (empty($index)) {
            return;
        }

        $this->map[$this->buildKey((string)$index)] = $key;
    }

    /**
     * @param string|int|null $index
     */
    private function mapUnset($index): void
    {
        if (empty($index)) {
            return;
        }

        unset($this->map[$this->buildKey((string)$index)]);
    }

    private function buildKey(string $index): string
    {
        return ctype_alnum($index) && mb_strlen($index, '8bit') <= 32 ? $index : md5($index);
    }
}
