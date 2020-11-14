<?php
declare(strict_types=1);

namespace kuaukutsu\ds\collection;

use ArrayIterator;
use Ds\Collection as PhpDsCollection;
use Ds\Traits\GenericCollection;

/**
 * Class Collection
 *
 * @see https://www.php.net/manual/class.ds-collection.php
 */
abstract class Collection implements PhpDsCollection
{
    use GenericCollection;

    private array $items = [];

    /**
     * Type object, get_class($item)
     *
     * @return string
     */
    abstract protected function getType(): string;

    /**
     * Collection constructor.
     * @param object ...$items
     */
    public function __construct(...$items)
    {
        foreach ($items as $item) {
            $this->attach($item);
        }
    }

    /**
     * Adds an object in the storage
     * @param object $object The object to add.
     * @return void
     * @throws CollectionTypeException
     */
    public function attach(object $object): void
    {
        if (is_a($object, $this->getType())) {
            $this->items[spl_object_hash($object)] ??= $object;
            return;
        }

        throw new CollectionTypeException('The collection item must be an instance of type ' . ucfirst($this->getType()));
    }

    /**
     * Adds all objects from another storage
     * @param Collection $collection
     */
    public function merge(self $collection): void
    {
        /** @var object $item */
        foreach ($collection as $item) {
            $this->attach($item);
        }
    }

    /**
     * Removes an object from the storage.
     * @param object $object
     */
    public function detach(object $object): void
    {
        unset($this->items[spl_object_hash($object)]);
    }

    /**
     * Checks if the storage contains a specific object.
     * @param object $object
     * @return bool
     */
    public function contains(object $object): bool
    {
        return array_key_exists(spl_object_hash($object), $this->items);
    }

    /**
     * Returns the number of objects in the storage.
     * @return int
     */
    public function count() : int
    {
        return count($this->items);
    }

    /**
     * Removes objects from the current storage.
     */
    public function clear(): void
    {
        $this->items = [];
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    public function toArray() : array
    {
        return array_values($this->items);
    }
}
