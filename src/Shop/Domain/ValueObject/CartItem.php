<?php

declare(strict_types=1);

namespace Src\Shop\Domain\ValueObject;

use Src\Shared\Domain\ValueObject\ValueObject;

final class CartItem extends ValueObject
{
    public function __construct(
        private ProductId        $productId,
        private QuantityCartItem $quantityCartItem
    ) {}

    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    public function getQuantityCartItem(): QuantityCartItem
    {
        return $this->quantityCartItem;
    }

    public function getValue(): string
    {
        return $this->productId->getValue();
    }

    public function equalValues(ValueObject $o): bool
    {
        if (!$o instanceof CartItem) {
            return false;
        }
        return $o->getProductId()->getValue() === $this->getProductId()->getValue();
    }
}
