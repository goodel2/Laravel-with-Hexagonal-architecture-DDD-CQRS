<?php

declare(strict_types=1);

namespace Src\Shop\Application\Query\AmountInOtherCurrency;

class AmountInOtherCurrencyQuery
{
    public function __construct(
        private string $currency,
        private float $amount,
    ) {}

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
