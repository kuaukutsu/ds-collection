<?php

declare(strict_types=1);

namespace kuaukutsu\ds\collection;

use LogicException;

/**
 * Exception thrown when an illegal index was requested.
 */
final class CollectionOutOfRangeException extends LogicException
{
}
