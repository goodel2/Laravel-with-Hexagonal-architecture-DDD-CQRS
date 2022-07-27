<?php

declare(strict_types=1);

namespace Tests\Feature\Shop\Domain\ValueObject;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Src\Shared\Domain\ValueObject\Uuid;
use Src\Shop\Domain\ValueObject\ProductId;

/**
 * @group Domain
 */
class ProductIdTest extends TestCase
{
    public function testProductIdValidateDomainValueObject()
    {
        $this->expectException(InvalidArgumentException::class);
        new ProductId('wrong-id');
    }

    public function testProductIdIsStringDomainValueObject()
    {
        $productId = new ProductId(Uuid::random()->getValue());
        $this->assertIsString($productId->getValue());
    }

    public function testProductIdEqualsDomainValueObject()
    {
        $productIdOne = new ProductId(Uuid::random()->getValue());
        $productIdTwo = new ProductId($productIdOne->getValue());
        $this->assertEquals($productIdOne->getValue(), $productIdTwo->getValue());
    }
}
