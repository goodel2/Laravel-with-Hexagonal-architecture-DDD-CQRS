<?php

declare(strict_types=1);

namespace Src\Shop\Domain\ValueObject;

use Src\Shared\Domain\ValueObject\ValueObject;

class ProductPrice extends ValueObject
{
    public function __construct(
        private ProductId           $productId,
        private UnitProductPrice    $unitPrice,
        private UnitProductPrice    $unitDiscountPrice,
        private MinUnitsForDiscount $minUnitsForDiscount,
    ) {}

    public function getValue(): string
    {
        return $this->productId->getValue();
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    public function getPrice(): UnitProductPrice
    {
        return $this->getPriceWithNoDiscount();
    }

    public function getPriceWithNoDiscount(): UnitProductPrice
    {
        return $this->unitPrice;
    }

    public function getPriceWithDiscount(): UnitProductPrice
    {
        return $this->unitDiscountPrice;
    }

    public function getMinUnitsForDiscount(): MinUnitsForDiscount
    {
        return $this->minUnitsForDiscount;
    }

    public function getTotalPriceByTotalUnits(UnitProductPrice $totalUnits): UnitProductPrice
    {
        if ($totalUnits->getValue() >= $this->minUnitsForDiscount->getValue()) {
            return $this->getPriceWithDiscount();
        }
        return $this->getPriceWithNoDiscount();
    }

    protected function equalValues(ValueObject $o): bool
    {
        if (!$o instanceof ProductPrice) {
            return false;
        }

        return (
            $o->getProductId()->getValue() === $this->getProductId()->getValue() &&
            $o->getPriceWithNoDiscount()->getValue() === $this->getPriceWithNoDiscount()->getValue() &&
            $o->getPriceWithDiscount()->getValue() === $this->getPriceWithDiscount()->getValue() &&
            $o->getMinUnitsForDiscount()->getValue() === $this->getMinUnitsForDiscount()->getValue()
        );
    }
}
