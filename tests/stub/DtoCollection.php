<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection\tests\stub;

use kuaukutsu\ds\collection\Collection;

/**
 * @method iterable<Dto> getIterator()
 * @psalm-suppress ImplementedReturnTypeMismatch
 */
final class DtoCollection extends Collection
{
    public function getType(): string
    {
        return Dto::class;
    }

    public function getR(): array
    {
        $a = [];
        foreach ($this->getIterator() as $item) {
            $a[] = $item->id;
        }

        return $a;
    }
}
