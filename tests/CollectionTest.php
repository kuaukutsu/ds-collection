<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection\tests;

use stdClass;
use PHPUnit\Framework\TestCase;
use kuaukutsu\ds\collection\CollectionTypeException;
use kuaukutsu\ds\collection\tests\stub\Dto;
use kuaukutsu\ds\collection\tests\stub\DtoCollection;

final class CollectionTest extends TestCase
{
    public function testCollectionConstruct(): void
    {
        $collection = new DtoCollection(
            new Dto(1, 'first'),
            new Dto(2, 'second'),
            new Dto(3, 'third'),
        );

        self::assertCount(3, $collection);
    }

    public function testCollectionAdd(): void
    {
        $collection = new DtoCollection();
        $collection->attach(new Dto(1, 'first'));
        $collection->attach(new Dto(2, 'second'));

        self::assertCount(2, $collection);

        $collection->clear();

        self::assertCount(0, $collection);
    }

    public function testCollectionRemove(): void
    {
        $item = new Dto(2, 'second');

        $collection = new DtoCollection();
        $collection->attach(new Dto(1, 'first'));
        $collection->attach($item);

        self::assertCount(2, $collection);

        $collection->detach($item);

        self::assertCount(1, $collection);
    }

    public function testCollectionIterator(): void
    {
        $collection = new DtoCollection();
        $collection->attach(new Dto(1, 'first'));
        $collection->attach(new Dto(2, 'second'));

        self::assertContainsOnlyInstancesOf(Dto::class, $collection);

        foreach ($collection as $item) {
            self::assertInstanceOf(Dto::class, $item);
        }
    }

    public function testCollectionMerge(): void
    {
        $collection = new DtoCollection();
        $collection->attach(new Dto(1, 'first'));
        $collection->attach(new Dto(2, 'second'));

        $collectionSecond = new DtoCollection(new Dto(3, 'merge'));

        $collection->merge($collectionSecond);

        self::assertCount(3, $collection);

        /**
         * @var Dto $c1
         * @var Dto $c2
         * @var Dto $c3
         */
        [$c1, $c2, $c3] = $collection->toArray();

        self::assertEquals(1, $c1->id);
        self::assertEquals(2, $c2->id);
        self::assertEquals(3, $c3->id);
    }

    public function testCollectionFilter(): void
    {
        $collection = new DtoCollection();
        $collection->attach(new Dto(1, 'test'));
        $collection->attach(new Dto(2, 'second'));
        $collection->attach(new Dto(3, 'test'));

        $collectionNew = $collection->filter(static fn(Dto $dto): bool => $dto->name === 'test');

        self::assertCount(2, $collectionNew);

        /**
         * @var Dto $c1
         * @var Dto $c2
         */
        [$c1, $c2] = $collectionNew->toArray();

        self::assertEquals(1, $c1->id);
        self::assertEquals(3, $c2->id);
    }

    public function testCollectionOperations(): void
    {
        $object = new Dto(3, 'third');

        $collection = new DtoCollection();
        $collection->attach(new Dto(1, 'first'));
        $collection->attach(new Dto(2, 'second'));

        self::assertCount(2, $collection);

        self::assertFalse($collection->contains($object));

        $collection->attach($object);

        self::assertTrue($collection->contains($object));

        self::assertCount(3, $collection);

        $collection->detach($object);

        self::assertCount(2, $collection);

        $collection->clear();

        self::assertCount(0, $collection);
    }

    public function testCollectionArray(): void
    {
        $array = [
            new Dto(1, 'first'),
            new Dto(2, 'second'),
        ];

        $collection = new DtoCollection(...$array);

        self::assertEquals($array, $collection->toArray());
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

    public function testTypeExceptionCreateAdd(): void
    {
        $collection = new DtoCollection();

        $this->expectException(CollectionTypeException::class);

        /**
         * @psalm-suppress InvalidArgument exception
         * @phpstan-ignore argument.type
         */
        $collection->attach(new stdClass());
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

        // assert that memory increased by less than 2kb
        self::assertLessThan(2 * 1024, memory_get_usage() - $baseMemoryUsage);
    }
}
