<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection;

trait MapCollection
{
    /**
     * @var array<string, string>
     */
    private array $map = [];

    /**
     * @param string|int|array<scalar>|null $index
     */
    private function mapSet(string|int|array|null $index, string $key): void
    {
        if (empty($index) === false) {
            $this->map[$this->buildKey($index)] = $key;
        }
    }

    /**
     * @param string|int|array<scalar>|null $index
     */
    private function mapUnset(string|int|array|null $index): void
    {
        if (empty($index) === false) {
            unset($this->map[$this->buildKey($index)]);
        }
    }

    /**
     * @param string|int|array<scalar> $index
     */
    private function mapExists(string|int|array $index): ?string
    {
        return $this->map[$this->buildKey($index)] ?? null;
    }

    /**
     * @param string|int|array<scalar> $index
     */
    private function buildKey(string|int|array $index): string
    {
        if (is_array($index)) {
            $index = implode(':', $index);
        }

        if (is_numeric($index)) {
            $index = (string)$index;
        }

        return ctype_alnum($index) && mb_strlen($index, '8bit') <= 32 ? $index : hash('crc32b', $index);
    }
}
