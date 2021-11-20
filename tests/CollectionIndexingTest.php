<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection\tests;

use PHPUnit\Framework\TestCase;
use kuaukutsu\ds\collection\tests\stub\Dto;
use kuaukutsu\ds\collection\tests\stub\IndexCollection;
use kuaukutsu\ds\collection\tests\stub\IndexStringCollection;

final class CollectionIndexingTest extends TestCase
{
    public function testIndexingCollection(): void
    {
        $collection = new IndexCollection(
            new Dto(1, 'first'),
            new Dto(2, 'second'),
            new Dto(3, 'third'),
        );

        $dto = $collection->get(2);
        self::assertNotEmpty($dto);
        self::assertEquals(2, $dto->id);
    }

    public function testStringIndexingCollection(): void
    {
        $indexKey = 'длинное имя, чтобы проверить что ключ может быть любой строкой';

        $collection = new IndexStringCollection(
            new Dto(1, 'first'),
            new Dto(2, 'second'),
            new Dto(3, 'third'),
            new Dto(4, $indexKey),
        );

        $dto = $collection->get($indexKey);
        self::assertNotEmpty($dto);
        self::assertEquals(4, $dto->id);
    }
}
