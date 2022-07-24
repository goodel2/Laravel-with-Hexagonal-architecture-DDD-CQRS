<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Aggregate;

use Src\Shared\Domain\ValueObject\Uuid;

abstract class AggregateRoot
{
    abstract public function getId(): Uuid;
}
