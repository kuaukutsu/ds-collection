<?php
declare(strict_types=1);

namespace kuaukutsu\ds\collection\tests\stub;

use kuaukutsu\ds\collection\Collection;

/**
 * Class DtoCollection
 */
final class DtoCollection extends Collection
{
    /**
     * @psalm-return class-string
     * @return string
     */
    protected function getType(): string
    {
        return Dto::class;
    }
}
