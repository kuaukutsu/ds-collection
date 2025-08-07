<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection\tests\stub;

use stdClass;
use kuaukutsu\ds\collection\Collection;

/**
 * @extends Collection<stdClass>
 */
final class StdCollection extends Collection
{
    public function getType(): string
    {
        return stdClass::class;
    }
}
