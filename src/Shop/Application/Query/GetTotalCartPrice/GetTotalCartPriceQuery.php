<?php

declare(strict_types=1);

namespace Src\Shop\Application\Query\GetTotalCartPrice;

class GetTotalCartPriceQuery
{
    public function __construct(
        private string $cartId,
        private string $currency
    ) {}

    public function getCartId(): string
    {
        return $this->cartId;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
