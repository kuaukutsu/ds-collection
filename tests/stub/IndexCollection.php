<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection\tests\stub;

use Traversable;
use kuaukutsu\ds\collection\Collection;

/**
 * @extends Collection<Dto>
 * @method Traversable<Dto> getIterator()
 * @method Dto getFirst()
 * @method Dto getLast()
 * @method Dto[] toArray()
 */
final class IndexCollection extends Collection
{
    public function getType(): string
    {
        return Dto::class;
    }

    /**
     * @param Dto|object $item
     */
    protected function indexBy($item): int
    {
        return (int)$item->id;
    }
}
