<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection\tests\stub;

use kuaukutsu\ds\collection\Collection;

final class IndexCollection extends Collection
{
    public function getType(): string
    {
        return Dto::class;
    }

    /**
     * @param Dto|object $item
     * @return int
     * @psalm-suppress MixedReturnStatement
     */
    protected function indexBy(object $item): int
    {
        return $item->id;
    }
}
