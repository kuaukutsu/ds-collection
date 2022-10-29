<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection;

use Countable;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/**
 * @template T of object
 * @extends IteratorAggregate
 */
interface CollectionInterface extends IteratorAggregate, Countable, JsonSerializable
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
     * Returns a shallow copy of the collection.
     *
     * @return CollectionInterface a copy of the collection.
     * @psalm-immutable
     */
    public function copy(): self;

    /**
     * Returns whether the collection is empty.
     *
     * This should be equivalent to a count of zero, but is not required.
     * Implementations should define what empty means in their own context.
     */
    public function isEmpty(): bool;

    /**
     * Returns the size of the collection.
     *
     * @return int
     */
    public function count(): int;

    /**
     * Removes all values from the collection.
     */
    public function clear(): void;

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
     *
     * @param callable(T): bool $callback
     * @return static
     * @psalm-immutable
     */
    public function filter(callable $callback): self;

    /**
     * Returns objects by first index key.
     *
     * @return T
     * @throws CollectionOutOfRangeException
     * @psalm-immutable
     */
    public function getFirst(): object;

    /**
     * Returns objects by last index key.
     *
     * @return T
     * @throws CollectionOutOfRangeException
     * @psalm-immutable
     */
    public function getLast(): object;

    /**
     * Returns objects by index key.
     *
     * @param string|int ...$indexKey
     * @return T|null
     * @psalm-immutable
     */
    public function get(...$indexKey): ?object;

    /**
     * @return Traversable
     * @psalm-immutable
     */
    public function getIterator(): Traversable;

    /**
     * Returns an array representation of the collection.
     *
     * The format of the returned array is implementation-dependent.
     * Some implementations may throw an exception if an array representation
     * could not be created.
     *
     * @return array<T>
     * @psalm-immutable
     */
    public function toArray(): array;
}
