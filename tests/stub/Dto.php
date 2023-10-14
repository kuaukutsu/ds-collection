<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection\tests\stub;

final class Dto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {
    }
}
