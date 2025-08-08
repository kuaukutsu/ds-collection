<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection\internal;

/**
 * @return non-empty-string
 */
function generateKeyForObject(object $item): string
{
    /**
     * @var non-empty-string
     */
    return spl_object_hash($item);
}

/**
 * @param non-empty-string|int|non-empty-array<scalar> $index
 * @return non-empty-string
 */
function generateKeyForIndex(string | int | array $index): string
{
    if (is_array($index)) {
        $index = implode(':', $index);
    }

    return hash('xxh3', (string)$index);
}
