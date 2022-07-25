<?php

declare(strict_types=1);

namespace Src\Shop\Application\Query\AmountInOtherCurrency;

class AmountInOtherCurrencyDTO
{
    public function __construct(
        private string $newCurrency,
        private float $newAmount,
        private string $oldCurrency,
        private float $oldAmount
    ) {}

    public function getNewCurrency(): string
    {
        return $this->newCurrency;
    }

    public function getNewAmount(): float
    {
        return $this->newAmount;
    }

    public function getOldCurrency(): string
    {
        return $this->oldCurrency;
    }

    public function getOldAmount(): float
    {
        return $this->oldAmount;
    }
}
