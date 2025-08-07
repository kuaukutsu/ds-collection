<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection\internal;

/**
 * @psalm-internal kuaukutsu\ds\collection
 */
final class Index
{
    /**
     * @var array<non-empty-string, non-empty-string>
     */
    private array $index = [];

    /**
     * @param string|int|array<scalar>|null $index
     * @param non-empty-string $key
     */
    public function set(string | int | array | null $index, string $key): void
    {
        if ($index !== null && $index !== '' && $index !== []) {
            $this->index[generateKeyForIndex($index)] = $key;
        }
    }

    /**
     * @param string|int|array<scalar> $index
     * @return non-empty-string|null
     */
    public function get(string | int | array $index): ?string
    {
        if ($index !== '' && $index !== []) {
            return $this->index[generateKeyForIndex($index)] ?? null;
        }

        return null;
    }

    /**
     * @param string|int|array<scalar>|null $index
     */
    public function unset(string | int | array | null $index): void
    {
        if ($index !== null && $index !== '' && $index !== []) {
            unset($this->index[generateKeyForIndex($index)]);
        }
    }
}
