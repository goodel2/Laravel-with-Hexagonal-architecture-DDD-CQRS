<?php

declare(strict_types=1);

namespace Src\Shared\Domain\ValueObject;

abstract class StringValueObject extends ValueObject
{
    public function __construct(
        protected string $value
    ) {}

    public function getValue(): string
    {
        return $this->value;
    }
}
