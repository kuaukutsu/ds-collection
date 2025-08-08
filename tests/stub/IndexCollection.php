<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection\tests\stub;

use kuaukutsu\ds\collection\Collection;

/**
 * @extends Collection<Dto>
 */
final class IndexCollection extends Collection
{
    public function getType(): string
    {
        return Dto::class;
    }

    /**
     * @param Dto $item
     */
    protected function indexBy($item): int
    {
        return $item->id;
    }
}
