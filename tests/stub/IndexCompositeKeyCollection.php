<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection\tests\stub;

use kuaukutsu\ds\collection\Collection;

final class IndexCompositeKeyCollection extends Collection
{
    public function getType(): string
    {
        return Dto::class;
    }

    /**
     * @param Dto|object $item
     * @return array<scalar>
     */
    protected function indexBy(object $item): array
    {
        return [(int)$item->id, (string)$item->name];
    }
}
