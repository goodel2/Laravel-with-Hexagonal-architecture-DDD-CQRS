<?php

declare(strict_types=1);

namespace Src\Shop\Domain\ValueObject;

use Src\Shared\Domain\ValueObject\ValueObject;

final class Price extends ValueObject
{
    public function __construct(
        private Currency $currency,
        private Amount $amount
    ) {}

    public function getValue(): float
    {
        return $this->amount->getValue();
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getAmount(): Amount
    {
        return $this->amount;
    }

    public function toString(): string
    {
        return $this->getAmount()->getValue().' '.$this->getCurrency()->getValue();
    }

    protected function equalValues(ValueObject $o): bool
    {
        if (!$o instanceof Price) {
            return false;
        }

        return (
            $o->getCurrency()->getValue() === $this->getCurrency()->getValue() &&
            $o->getAmount()->getValue() === $this->getAmount()->getValue()
        );
    }
}
