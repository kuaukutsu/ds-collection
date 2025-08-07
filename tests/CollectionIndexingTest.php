<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection\tests;

use PHPUnit\Framework\TestCase;
use kuaukutsu\ds\collection\tests\stub\Dto;
use kuaukutsu\ds\collection\tests\stub\IndexCollection;
use kuaukutsu\ds\collection\tests\stub\IndexCompositeKeyCollection;
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

        // positive
        $dto = $collection->get(2);
        self::assertNotEmpty($dto);
        self::assertEquals(2, $dto->id);

        // negative
        $dto = $collection->get(5);
        self::assertEmpty($dto);
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

        // positive
        $dto = $collection->get($indexKey);
        self::assertNotEmpty($dto);
        self::assertEquals(4, $dto->id);
    }

    public function testCompositeKeyIndexingCollection(): void
    {
        $collection = new IndexCompositeKeyCollection(
            new Dto(1, 'one'),
            new Dto(2, 'two'),
            new Dto(3, 'three'),
            new Dto(2, 'four'),
        );

        // positive
        $dto = $collection->get(2, 'two');
        self::assertNotEmpty($dto);
        self::assertEquals(2, $dto->id);

        // negative
        $dto = $collection->get(3, 'two');
        self::assertEmpty($dto);
    }

    public function testCollectionRemove(): void
    {
        $item = new Dto(2, 'second');

        $collection = new IndexCollection();
        $collection->attach(new Dto(1, 'first'));
        $collection->attach($item);
        $collection->detach($item);

        self::assertCount(1, $collection);
        self::assertEmpty($collection->get(2));
    }
}
