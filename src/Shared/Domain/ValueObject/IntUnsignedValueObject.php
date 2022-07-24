<?php

declare(strict_types=1);

namespace Src\Shared\Domain\ValueObject;

use Src\Shared\Domain\Validators\CommonValidator;

abstract class IntUnsignedValueObject extends ValueObject
{
    public function __construct(
        protected int $value
    ) {
        $this->validate($value);
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function isBiggerThan(IntUnsignedValueObject $other): bool
    {
        return $this->getValue() > $other->getValue();
    }

    protected function validate(int $value): void
    {
        CommonValidator::validateIntGreaterOrEqualZero($value);
    }
}
