<?php

declare(strict_types=1);

namespace Src\Shop\Application\Command\RemoveCartItem;

class RemoveCartItemCommand
{
    public function __construct(
        private string $cartId,
        private string $productId
    ) {}

    public function getCartId(): string
    {
        return $this->cartId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }
}
