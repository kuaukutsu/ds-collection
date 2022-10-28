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
     * @param string $key
     */
    private function mapSet($index, string $key): void
    {
        if (empty($index) === false) {
            $this->map[$this->buildKey($index)] = $key;
        }
    }

    /**
     * @param string|int|array<scalar>|null $index
     */
    private function mapUnset($index): void
    {
        if (empty($index) === false) {
            unset($this->map[$this->buildKey($index)]);
        }
    }

    /**
     * @param string|int|array<scalar> $index
     * @return string
     */
    private function buildKey($index): string
    {
        if (is_array($index)) {
            $index = implode(':', $index);
        }

        if (is_numeric($index)) {
            $index = (string)$index;
        }

        return ctype_alnum($index) && mb_strlen($index, '8bit') <= 32 ? $index : md5($index);
    }
}
