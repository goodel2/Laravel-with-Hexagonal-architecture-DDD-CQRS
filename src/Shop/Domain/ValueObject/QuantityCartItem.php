<?php

declare(strict_types=1);

namespace Src\Shop\Domain\ValueObject;

use Src\Shared\Domain\ValueObject\IntUnsignedValueObject;
use Src\Shared\Domain\ValueObject\ValueObject;

final class QuantityCartItem extends IntUnsignedValueObject
{
    protected function equalValues(ValueObject $o): bool
    {
        return ($o->getValue()->equals($this->getValue()));
    }
}
