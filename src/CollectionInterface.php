<?php
declare(strict_types=1);

namespace kuaukutsu\ds\collection;

use Ds\Collection as PhpDsCollection;

interface CollectionInterface extends PhpDsCollection
{
    /**
     * Adds an object in the storage.
     *
     * @param object $object The object to add.
     * @return void
     * @throws CollectionTypeException
     */
    public function attach(object $object): void;

    /**
     * Removes an object from the storage.
     *
     * @param object $object
     */
    public function detach(object $object): void;

    /**
     * Checks if the storage contains a specific object.
     *
     * @param object $object
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
     * @example
     * ```php
     * function(object $object): bool {
     *  return get_class($object) === $this->getType();
     * }
     * ```
     *
     * @return static
     */
    public function filter(callable $callback): self;
}
