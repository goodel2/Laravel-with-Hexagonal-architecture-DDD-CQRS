<?php

declare(strict_types=1);

namespace Src\Shop\Application\Command\AddCartItem;

class AddCartItemCommand
{
    public function __construct(
        private string $cartId,
        private string $productId,
        private int    $quantityCartItem
    ) {}

    public function getCartId(): string
    {
        return $this->cartId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getQuantityCartItem(): int
    {
        return $this->quantityCartItem;
    }
}
