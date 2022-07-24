<?php

declare(strict_types=1);

namespace Src\Shared\Domain\ValueObject;

use Src\Shared\Domain\Validators\CommonValidator;

abstract class FloatUnsignedValueObject extends ValueObject
{
    public function __construct(
        private float $value
    ) {
        $this->validate($value);
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function isBiggerThan(FloatUnsignedValueObject $other): bool
    {
        return $this->getValue() > $other->getValue();
    }

    protected function validate(float $value): void
    {
        CommonValidator::validateFloatGreaterOrEqualZero($value);
    }
}
