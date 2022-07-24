<?php

declare(strict_types=1);

namespace Tests\Unit\Shop\Domain\Aggregate;

use PHPUnit\Framework\TestCase;
use Src\Shared\Domain\ValueObject\Uuid;
use Src\Shop\Domain\Aggregate\Product;
use Src\Shop\Domain\ValueObject\ProductPrice;
use Src\Shop\Domain\ValueObject\MinUnitsForDiscount;
use Src\Shop\Domain\ValueObject\ProductId;
use Src\Shop\Domain\ValueObject\UnitProductPrice;

/**
 * @group Domain
 */
class ProductTest extends TestCase
{
    private function buildProduct($productId, $unitProductPrice, $unitDiscountProductPrice, $minUnitsForDiscount)
    {
        $productPrice = new ProductPrice(
            new ProductId($productId),
            new UnitProductPrice($unitProductPrice),
            new UnitProductPrice($unitDiscountProductPrice),
            new MinUnitsForDiscount($minUnitsForDiscount),
        );

        return new Product(new ProductId($productId), $productPrice);
    }

    public function testCheckProductPriceDomainAggregate()
    {
        $productId = Uuid::random()->getValue();
        $unitProductPrice = 5.95;
        $unitDiscountProductPrice = 4.95;
        $minUnitsForDiscount = 4;
        $product = $this->buildProduct($productId, $unitProductPrice, $unitDiscountProductPrice, $minUnitsForDiscount);

        $this->assertEquals($product->getId(), $productId);
        $this->assertEquals($product->getProductPrice()->getProductId(), $productId);
        $this->assertEquals($product->getProductPrice()->getPriceWithNoDiscount()->getValue(), $unitProductPrice);
        $this->assertEquals($product->getProductPrice()->getPriceWithDiscount()->getValue(), $unitDiscountProductPrice);
        $this->assertEquals($product->getProductPrice()->getMinUnitsForDiscount()->getValue(), $minUnitsForDiscount);

        $this->assertEquals($product->getProductPrice()->getTotalPriceByTotalUnits(new UnitProductPrice(3))->getValue(),
            $product->getProductPrice()->getPriceWithNoDiscount()->getValue());

        $this->assertEquals($product->getProductPrice()->getTotalPriceByTotalUnits(new UnitProductPrice(4))->getValue(),
            $product->getProductPrice()->getPriceWithDiscount()->getValue());

        $this->assertEquals($product->getProductPrice()->getTotalPriceByTotalUnits(new UnitProductPrice(5))->getValue(),
            $product->getProductPrice()->getPriceWithDiscount()->getValue());
    }
}
