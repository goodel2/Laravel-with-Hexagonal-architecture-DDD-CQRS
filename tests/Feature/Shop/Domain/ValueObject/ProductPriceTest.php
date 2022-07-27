<?php

declare(strict_types=1);

namespace Tests\Feature\Shop\Domain\Aggregate;

use PHPUnit\Framework\TestCase;
use Src\Shared\Domain\ValueObject\Uuid;
use Src\Shop\Domain\ValueObject\ProductPrice;
use Src\Shop\Domain\ValueObject\MinUnitsForDiscount;
use Src\Shop\Domain\ValueObject\ProductId;
use Src\Shop\Domain\ValueObject\UnitProductPrice;

/**
 * @group Domain
 */
class ProductPriceTest extends TestCase
{
    private function buildProductPrice($productId, $unitProductPrice, $unitDiscountProductPrice, $minUnitsForDiscount)
    {
        return new ProductPrice(
            new ProductId($productId),
            new UnitProductPrice($unitProductPrice),
            new UnitProductPrice($unitDiscountProductPrice),
            new MinUnitsForDiscount($minUnitsForDiscount),
        );
    }

    public function testCheckProductPriceDomainAggregate()
    {
        $productId = Uuid::random()->getValue();
        $unitProductPrice = 5.95;
        $unitDiscountProductPrice = 4.95;
        $minUnitsForDiscount = 4;
        $productPrice = $this->buildProductPrice($productId, $unitProductPrice, $unitDiscountProductPrice, $minUnitsForDiscount);

        $this->assertEquals($productPrice->getProductId(), $productId);
        $this->assertEquals($productPrice->getPriceWithNoDiscount()->getValue(), $unitProductPrice);
        $this->assertEquals($productPrice->getPriceWithDiscount()->getValue(), $unitDiscountProductPrice);
        $this->assertEquals($productPrice->getMinUnitsForDiscount()->getValue(), $minUnitsForDiscount);

        $this->assertEquals($productPrice->getTotalPriceByTotalUnits(new UnitProductPrice(3))->getValue(),
            $productPrice->getPriceWithNoDiscount()->getValue());

        $this->assertEquals($productPrice->getTotalPriceByTotalUnits(new UnitProductPrice(4))->getValue(),
            $productPrice->getPriceWithDiscount()->getValue());

        $this->assertEquals($productPrice->getTotalPriceByTotalUnits(new UnitProductPrice(5))->getValue(),
            $productPrice->getPriceWithDiscount()->getValue());
    }
}
