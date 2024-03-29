<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection\tests\stub;

use kuaukutsu\ds\collection\Collection;

/**
 * @extends Collection<Dto>
 */
final class DtoCollection extends Collection
{
    public function getType(): string
    {
        return Dto::class;
    }
}
