<?php
declare(strict_types=1);

namespace kuaukutsu\ds\collection\tests\stub;

final class Dto
{
    public int $id;

    public string $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}
