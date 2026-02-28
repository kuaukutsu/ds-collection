<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection\tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use kuaukutsu\ds\collection\internal\Index;

final class IndexTest extends TestCase
{
    /**
     * @param string|int|array<scalar>|null $index
     * @param non-empty-string $key
     */
    #[DataProvider('additionProvider')]
    public function testIndex(string | int | array | null $index, string $key, ?string $expected): void
    {
        $data = new Index();
        $data->set('init', 'init');

        $data->set($index, $key);
        if ($index === null || $index === '' || $index === []) {
            self::assertEmpty($expected);
            return;
        }

        self::assertEquals($expected, $data->get($index));

        if ($expected !== null) {
            $data->unset($index);
            self::assertEmpty($data->get($index));
        }
    }

    /**
     * @return iterable<array{
     *     0: string|int|array<string|int>|null,
     *     1: non-empty-string,
     *     2: non-empty-string|null}>
     */
    public static function additionProvider(): iterable
    {
        return [
            ['test', 'key1', 'key1'],
            [[1,2,3], 'key2', 'key2'],
            [['str', 'str'], 'key3', 'key3'],
            ['', 'empty', null],
            [[], 'empty', null],
            [null, 'empty', null],
        ];
    }
}
