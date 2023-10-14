<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection\tests\stub;

use kuaukutsu\ds\collection\Collection;

/**
 * @extends Collection<Dto>
 */
final class IndexCompositeKeyCollection extends Collection
{
    public function getType(): string
    {
        return Dto::class;
    }

    /**
     * @param Dto $item
     * @return array<scalar>
     */
    protected function indexBy($item): array
    {
        return [$item->id, $item->name];
    }
}
