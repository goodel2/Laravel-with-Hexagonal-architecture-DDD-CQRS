<?php

declare(strict_types=1);

namespace Src\Shop\Domain\ValueObject;

use Src\Shared\Domain\ValueObject\IntUnsignedValueObject;
use Src\Shared\Domain\ValueObject\ValueObject;

final class MinUnitsForDiscount extends IntUnsignedValueObject
{
    protected function equalValues(ValueObject $o): bool
    {
        if (!$o instanceof MinUnitsForDiscount) {
            return false;
        }

        return $o->getValue() === $this->getValue();
    }
}
