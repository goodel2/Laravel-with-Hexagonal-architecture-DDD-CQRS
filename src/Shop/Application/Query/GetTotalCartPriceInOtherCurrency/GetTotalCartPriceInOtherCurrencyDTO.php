<?php

declare(strict_types=1);

namespace Src\Shop\Application\Query\GetTotalCartPriceInOtherCurrency;

class GetTotalCartPriceInOtherCurrencyDTO
{
    public function __construct(
        private string $currency,
        private float  $priceWithDiscount,
        private float  $priceWithNoDiscount
    ) {}

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getPriceWithDiscount(): float
    {
        return $this->priceWithDiscount;
    }

    public function getPriceWithNoDiscount(): float
    {
        return $this->priceWithNoDiscount;
    }
}
