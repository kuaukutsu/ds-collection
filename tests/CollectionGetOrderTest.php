<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection\tests;

use PHPUnit\Framework\TestCase;
use kuaukutsu\ds\collection\CollectionOutOfRangeException;
use kuaukutsu\ds\collection\tests\stub\Dto;
use kuaukutsu\ds\collection\tests\stub\IndexCollection;

final class CollectionGetOrderTest extends TestCase
{
    public function testFirstCollection(): void
    {
        $collection = new IndexCollection(
            new Dto(1, 'first'),
            new Dto(2, 'second'),
            new Dto(3, 'third'),
        );

        // positive
        $dto = $collection->getFirst();
        self::assertNotEmpty($dto);
        self::assertEquals(1, $dto->id);
    }

    public function testLastCollection(): void
    {
        $collection = new IndexCollection(
            new Dto(1, 'first'),
            new Dto(2, 'second'),
            new Dto(3, 'third'),
        );

        // positive
        $dto = $collection->getLast();
        self::assertNotEmpty($dto);
        self::assertEquals(3, $dto->id);
    }

    public function testOutOfRangeExceptionGetFirstConstruct(): void
    {
        $collection = new IndexCollection();

        $this->expectException(CollectionOutOfRangeException::class);

        $collection->getFirst();
    }

    public function testOutOfRangeExceptionGetLastConstruct(): void
    {
        $collection = new IndexCollection();

        $this->expectException(CollectionOutOfRangeException::class);

        $collection->getLast();
    }

    public function testFirstByFilterCollection(): void
    {
        $collection = new IndexCollection(
            new Dto(1, 'first'),
            new Dto(2, 'second'),
            new Dto(3, 'third'),
            new Dto(4, 'fourth'),
        );

        // positive
        $dto = $collection->getFirst();
        self::assertNotEmpty($dto);
        self::assertEquals(1, $dto->id);

        // filter
        $collectionNew = $collection->filter(static function (Dto $dto): bool {
            return ($dto->id % 2) === 0;
        });

        // old collection
        $dto = $collection->getFirst();
        self::assertNotEmpty($dto);
        self::assertEquals(1, $dto->id);

        // new collection
        $dto = $collectionNew->getFirst();
        self::assertNotEmpty($dto);
        self::assertEquals(2, $dto->id);
    }
}
