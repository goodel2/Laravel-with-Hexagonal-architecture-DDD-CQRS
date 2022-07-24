<?php

declare(strict_types=1);

namespace Src\Shop\Domain\ValueObject;

use Src\Shared\Domain\ValueObject\FloatUnsignedValueObject;
use Src\Shared\Domain\ValueObject\ValueObject;

final class Amount extends FloatUnsignedValueObject
{
    protected function equalValues(ValueObject $o): bool
    {
        if (!$o instanceof Amount) {
            return false;
        }

        return $o->getValue() === $this->getValue();
    }
}
