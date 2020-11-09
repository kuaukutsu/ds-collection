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
     * @param mixed ...$items
     */
    public function __construct(...$items)
    {
        foreach ($items as $item) {
            $this->add($item);
        }
    }

    /**
     * Adds an object in the storage
     * @param object $object The object to add.
     * @return void
     */
    public function add(object $object): void
    {
        if (is_a($object, $this->getType())) {
            $this->items[] = $object;
            return;
        }

        throw new CollectionTypeException('The collection item must be an instance of type ' . ucfirst($this->getType()));
    }

    public function clear(): void
    {
        $this->items = [];
    }

    public function count() : int
    {
        return count($this->items);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    public function toArray() : array
    {
        return $this->items;
    }
}
