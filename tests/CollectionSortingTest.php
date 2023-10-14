<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection\tests;

use PHPUnit\Framework\TestCase;
use kuaukutsu\ds\collection\tests\stub\Dto;
use kuaukutsu\ds\collection\tests\stub\IndexCollection;

final class CollectionSortingTest extends TestCase
{
    public function testSortNameCollection(): void
    {
        $collection = new IndexCollection(
            new Dto(1, 'b'),
            new Dto(2, 'a'),
            new Dto(3, 'c'),
        );

        $sortCollection = $collection->sort(
            static fn(Dto $a, Dto $b): int => strcmp($a->name, $b->name)
        );

        // positive
        self::assertEquals(1, $collection->getFirst()->id);
        self::assertEquals(2, $sortCollection->getFirst()->id);

        // negative
        self::assertNotEquals(1, $sortCollection->getLast()->id);
    }
}
