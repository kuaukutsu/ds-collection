<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection;

trait IndexCollection
{
    /**
     * @var array<string, string>
     */
    private array $index = [];

    /**
     * @param string|int|array<scalar>|null $index
     */
    private function mapSet(string | int | array | null $index, string $key): void
    {
        if ($index !== null && $index !== '' && $index !== []) {
            $this->index[$this->buildKey($index)] = $key;
        }
    }

    private function mapUnset(string $key): void
    {
        $mapKey = array_search($key, $this->index, true);
        if ($mapKey !== false) {
            unset($this->index[$mapKey]);
        }
    }

    /**
     * @param string|int|array<scalar> $index
     */
    private function mapSearch(string | int | array $index): ?string
    {
        return $this->index[$this->buildKey($index)] ?? null;
    }

    /**
     * @param string|int|array<scalar> $index
     */
    private function buildKey(string | int | array $index): string
    {
        if (is_array($index)) {
            $index = implode(':', $index);
        }

        return hash('xxh3', (string)$index);
    }

    private function mapClear(): void
    {
        $this->index = [];
    }
}
