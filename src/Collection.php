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
     * @param T|object $object The object to add.
     * @return void
     * @throws CollectionTypeException
     */
    final public function attach(object $object): void
    {
        if (is_a($object, $this->getType())) {
            $this->items[spl_object_hash($object)] ??= $object;
            return;
        }

        throw new CollectionTypeException(
            'The collection item must be an instance of type ' . ucfirst($this->getType())
        );
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
     * @param T|object $object
     */
    final public function detach(object $object): void
    {
        unset($this->items[spl_object_hash($object)]);
    }

    /**
     * Checks if the storage contains a specific object.
     * @param T|object $object
     * @return bool
     */
    final public function contains(object $object): bool
    {
        return array_key_exists(spl_object_hash($object), $this->items);
    }

    /**
     * Filters elements of an array using a callback function.
     * @param callable(mixed):bool $callback
     * @example
     * ```php
     * function(object $object): bool {
     *  return get_class($object) === $this->getType();
     * }
     * ```
     *
     * @return static
     */
    final public function filter(callable $callback): self
    {
        $new = clone $this;
        $new->items = array_filter($this->items, $callback);

        return $new;
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
}
