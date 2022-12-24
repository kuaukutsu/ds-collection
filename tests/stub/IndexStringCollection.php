<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection\tests\stub;

use Traversable;
use kuaukutsu\ds\collection\Collection;

/**
 * @extends Collection<Dto>
 * @method Traversable<Dto> getIterator()
 */
final class IndexStringCollection extends Collection
{
    public function getType(): string
    {
        return Dto::class;
    }

    /**
     * @param Dto|object $item
     * @return string
     */
    protected function indexBy($item): string
    {
        return (string)$item->name;
    }
}
