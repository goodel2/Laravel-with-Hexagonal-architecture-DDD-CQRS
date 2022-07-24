<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Collections;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Src\Shared\Domain\Utils;

abstract class Collection implements Countable, IteratorAggregate
{
    public function __construct(
        private array $items
    ) {
        Utils::arrayOf($this->getType(), $items);
    }

    abstract protected function getType(): string;

    public function getItems(): array
    {
        return $this->items;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->getItems());
    }

    public function count(): int
    {
        return count($this->getItems());
    }
}
