<?php

namespace kuaukutsu\ds\collection\tests;

use kuaukutsu\ds\collection\CollectionTypeException;
use PHPUnit\Framework\TestCase;
use stdClass;

class CollectionTest extends TestCase
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

        $collection->detach($object);

        self::assertCount(2, $collection);

        $collection->clear();

        self::assertCount(0, $collection);
    }

    public function testCollectionArray(): void
    {
        $array = [
            new Dto(1, 'first'),
            new Dto(2, 'second')
        ];

        $collection = new DtoCollection(...$array);

        self::assertEquals($array, $collection->toArray());
    }

    public function testTypeExceptionCreateConstruct(): void
    {
        $this->expectException(CollectionTypeException::class);

        new DtoCollection(new stdClass());
    }

    public function testTypeExceptionCreateAdd(): void
    {
        $collection = new DtoCollection();

        $this->expectException(CollectionTypeException::class);

        $collection->attach(new stdClass());
    }

    public function testDoesNotLeakMemory(): void
    {
        $collection = new DtoCollection();
        $baseMemoryUsage = memory_get_usage();

        for ($i = 0; $i < 100; ++$i) {
            $data = new Dto(1, 'test');
            $collection->attach($data);
            $collection->detach($data);
        }

        // assert that memory increased by less than 2kb
        self::assertLessThan(2 * 1024, memory_get_usage() - $baseMemoryUsage);
    }
}
