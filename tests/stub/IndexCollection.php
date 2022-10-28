<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection\tests\stub;

use kuaukutsu\ds\collection\Collection;

/**
 * @method \Traversable<Dto> getIterator()
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method Dto getFirst();
 * @method Dto getLast();
 */
final class IndexCollection extends Collection
{
    public function getType(): string
    {
        return Dto::class;
    }

    /**
     * @param Dto|object $item
     * @return int
     */
    protected function indexBy(object $item): int
    {
        return (int)$item->id;
    }
}
