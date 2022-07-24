<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Validators;

use Src\Shared\Domain\Exceptions\InvalidInputDomainException;

class CommonValidator
{
    public static function validateNotEmptyString(string $string): void
    {
        if (empty($string)) {
            throw new InvalidInputDomainException('String is empty: ' . $string);
        }
    }

    public static function validateFloatGreaterOrEqualZero(float $value): void
    {
        if ($value < 0) {
            throw new InvalidInputDomainException('Value is less than 0: ' . $value);
        }
    }

    public static function validateIntGreaterOrEqualZero(int $value): void
    {
        if ($value < 0) {
            throw new InvalidInputDomainException('Value is less than 0: ' . $value);
        }
    }

    public static function validateClassComparingObjects(object $firstObject, object $secondObject): void
    {
        if (!$firstObject instanceof $secondObject && !$secondObject instanceof $firstObject) {
            throw new InvalidInputDomainException(sprintf('Classes are not the same %s and %s', $firstObject::class, $secondObject::class));
        }
    }
}
