<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection;

use Traversable;
use Ds\Collection as PhpDsCollection;

/**
 * @template T of object
 */
interface CollectionInterface extends PhpDsCollection
{
    /**
     * Type object, get_class($item)
     *
     * @return class-string<T>
     */
    public function getType(): string;

    /**
     * @return Traversable
     */
    public function getIterator(): Traversable;

    /**
     * Adds an object in the storage.
     *
     * @param T $item The object to add.
     * @return void
     * @throws CollectionTypeException
     */
    public function attach(object $item): void;

    /**
     * Removes an object from the storage.
     *
     * @param T $item
     */
    public function detach(object $item): void;

    /**
     * Checks if the storage contains a specific object.
     *
     * @param T $item
     * @return bool
     */
    public function contains(object $item): bool;

    /**
     * Adds all objects from another storage.
     *
     * @param CollectionInterface $collection
     */
    public function merge(CollectionInterface $collection): void;

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
    public function filter(callable $callback): self;

    /**
     * Returns objects by index key.
     * @param string|int ...$indexKey
     * @return T|null
     */
    public function get(...$indexKey): ?object;
}
