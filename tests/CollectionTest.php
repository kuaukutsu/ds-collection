<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection\tests;

use stdClass;
use PHPUnit\Framework\TestCase;
use kuaukutsu\ds\collection\CollectionTypeException;
use kuaukutsu\ds\collection\tests\stub\Dto;
use kuaukutsu\ds\collection\tests\stub\DtoCollection;
use kuaukutsu\ds\collection\tests\stub\StdCollection;

final class CollectionTest extends TestCase
{
    public function testConstruct(): void
    {
        $collection = new DtoCollection(
            new Dto(1, 'first'),
            new Dto(2, 'second'),
            new Dto(3, 'third'),
        );

        self::assertCount(3, $collection);
    }

    public function testAddAndRemove(): void
    {
        $item = new Dto(3, 'third');

        $collection = new DtoCollection();
        $collection->attach(new Dto(1, 'first'));
        $collection->attach(new Dto(2, 'second'));
        $collection->attach($item);

        self::assertCount(3, $collection);

        $collection->detach($item);
        self::assertCount(2, $collection);

        $collection->clear();
        self::assertCount(0, $collection);
    }

    public function testIterator(): void
    {
        $collection = new DtoCollection();
        $collection->attach(new Dto(1, 'first'));
        $collection->attach(new Dto(2, 'second'));

        self::assertContainsOnlyInstancesOf(Dto::class, $collection);

        foreach ($collection as $item) {
            self::assertInstanceOf(Dto::class, $item);
        }
    }

    public function testMerge(): void
    {
        $collection = new DtoCollection();
        $collection->attach(new Dto(1, 'first'));
        $collection->attach(new Dto(2, 'second'));

        $collectionSecond = new DtoCollection(new Dto(3, 'merge'));
        $collection->merge($collectionSecond);
        self::assertCount(3, $collection);

        [$c1, $c2, $c3] = $collection->toArray();
        self::assertEquals(1, $c1->id);
        self::assertEquals(2, $c2->id);
        self::assertEquals(3, $c3->id);
    }

    public function testFilter(): void
    {
        $collection = new DtoCollection();
        $collection->attach(new Dto(1, 'test'));
        $collection->attach(new Dto(2, 'second'));
        $collection->attach(new Dto(3, 'test'));

        $collectionNew = $collection->filter(static fn(Dto $dto): bool => $dto->name === 'test');
        self::assertCount(2, $collectionNew);

        [$c1, $c2] = $collectionNew->toArray();
        self::assertEquals(1, $c1->id);
        self::assertEquals(3, $c2->id);
    }

    public function testContains(): void
    {
        $object = new Dto(3, 'third');

        $collection = new DtoCollection();
        $collection->attach(new Dto(1, 'first'));
        $collection->attach(new Dto(2, 'second'));

        self::assertFalse($collection->contains($object));

        $collection->attach($object);
        self::assertTrue($collection->contains($object));
    }

    public function testTypeExceptionCreateConstruct(): void
    {
        $this->expectException(CollectionTypeException::class);

        /**
         * @psalm-suppress InvalidArgument exception
         * @phpstan-ignore argument.type
         */
        new DtoCollection(new stdClass());
    }

    public function testTypeExceptionAttach(): void
    {
        $collection = new DtoCollection();

        $this->expectException(CollectionTypeException::class);

        /**
         * @psalm-suppress InvalidArgument exception
         * @phpstan-ignore argument.type
         */
        $collection->attach(new stdClass());
    }

    public function testTypeExceptionMerge(): void
    {
        $collection = new DtoCollection();
        $collectionMerge = new StdCollection(new stdClass());

        $this->expectException(CollectionTypeException::class);

        /**
         * @psalm-suppress InvalidArgument exception
         * @phpstan-ignore argument.type
         */
        $collection->merge($collectionMerge);
    }

    public function testDoesNotLeakMemory(): void
    {
        $collection = new DtoCollection();
        $baseMemoryUsage = memory_get_usage();

        for ($i = 0; $i < 1000; ++$i) {
            $data = new Dto(1, 'test');
            $collection->attach($data);
            $collection->detach($data);
        }

        // assert that memory increased by less than 1kb
        self::assertLessThan(1024, memory_get_usage() - $baseMemoryUsage);
    }
}
