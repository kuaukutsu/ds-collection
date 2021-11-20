<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection;

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
     * Adds an object in the storage.
     *
     * @param T|object $object The object to add.
     * @return void
     * @throws CollectionTypeException
     */
    public function attach(object $object): void;

    /**
     * Removes an object from the storage.
     *
     * @param T|object $object
     */
    public function detach(object $object): void;

    /**
     * Checks if the storage contains a specific object.
     *
     * @param T|object $object
     * @return bool
     */
    public function contains(object $object): bool;

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
     * function(object $object): bool {
     *  return get_class($object) === $this->getType();
     * }
     * ```
     *
     */
    public function filter(callable $callback): self;
}
