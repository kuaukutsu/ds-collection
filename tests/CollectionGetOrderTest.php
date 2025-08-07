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

        self::assertEquals(1, $collection->getFirst()->id);
    }

    public function testLastCollection(): void
    {
        $collection = new IndexCollection(
            new Dto(1, 'first'),
            new Dto(2, 'second'),
            new Dto(3, 'third'),
        );

        self::assertEquals(3, $collection->getLast()->id);
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

        self::assertEquals(1, $collection->getFirst()->id);

        // filter
        $collectionNew = $collection->filter(static fn(Dto $dto): bool => ($dto->id % 2) === 0);

        // old collection
        self::assertEquals(1, $collection->getFirst()->id);
        // new collection
        self::assertEquals(2, $collectionNew->getFirst()->id);
    }
}
