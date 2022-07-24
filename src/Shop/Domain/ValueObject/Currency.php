<?php

declare(strict_types=1);

namespace Src\Shop\Domain\ValueObject;

use ReflectionClass;
use Src\Shared\Domain\Exceptions\InvalidInputDomainException;
use Src\Shared\Domain\Validators\CommonValidator;
use Src\Shared\Domain\ValueObject\EnumValueObject;
use Src\Shared\Domain\ValueObject\ValueObject;

final class Currency extends EnumValueObject
{
    private const EUR  = 'EUR';
    private const USD  = 'USD';
    private const DEFAULT_CURRENCY = self::EUR;

    public function __construct(
        private string $currency
    ) {
        $this->validate($currency);
    }

    public static function getDefaultValue(): string
    {
        return self::DEFAULT_CURRENCY;
    }

    public function getValue(): string
    {
        return $this->currency;
    }

    public static function getEur(): string
    {
        return self::EUR;
    }

    public static function getUsd(): string
    {
        return self::USD;
    }

    public function isEur(): bool
    {
        return self::getEur() === $this->currency;
    }

    public function isUsd(): bool
    {
        return self::getUsd() === $this->currency;
    }

    private function getConstants(): array
    {
        $reflectionClass = new ReflectionClass($this);
        return $reflectionClass->getConstants();
    }

    protected function validate(string $currency): void
    {
        CommonValidator::validateNotEmptyString($currency);

        if (!array_search($currency, $this->getConstants())) {
            throw new InvalidInputDomainException(sprintf('Not valid value'));
        }
    }

    protected function equalValues(ValueObject $o): bool
    {
        if (!$o instanceof Currency) {
            return false;
        }

        return $o->getValue() === $this->getValue();
    }
}
