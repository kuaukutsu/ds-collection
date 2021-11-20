<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection\tests\stub;

use kuaukutsu\ds\collection\Collection;

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
    protected function indexBy(object $item): string
    {
        return (string)$item->name;
    }
}
