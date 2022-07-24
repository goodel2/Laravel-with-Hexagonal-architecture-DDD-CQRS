<?php

declare(strict_types=1);

namespace Src\Shop\Domain\ValueObject;

use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use Src\Shared\Domain\ValueObject\ValueObject;
use Src\Shop\Domain\Exceptions\MaxUnitPerProductDomainException;

final class CartItem extends ValueObject
{
    private const MAX_UNITS_PER_PRODUCT = 50;

    public function __construct(
        private ProductId        $productId,
        private QuantityCartItem $quantityCartItem
    ) {
        $this->validateMaxUnitsPerProduct($this->quantityCartItem);
    }

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

    public static function getMaxUnitsPerProduct(): mixed
    {
        return self::MAX_UNITS_PER_PRODUCT;
    }

    private function validateMaxUnitsPerProduct(QuantityCartItem $quantityCartItem): void
    {
        $maxUnitsPerProduct = self::getMaxUnitsPerProduct();
        $cartItemsLeft = $maxUnitsPerProduct - $quantityCartItem->getValue();
        if ($cartItemsLeft < 0) {
            $this->throwMaxUnitPerProductLeft($maxUnitsPerProduct);
        }
    }

    private function throwMaxUnitPerProductLeft(int $maxUnitsPerProduct): Throws
    {
        throw new MaxUnitPerProductDomainException(
            sprintf('You have got the maximum number of items in cart: %s', $maxUnitsPerProduct)
        );
    }
}
