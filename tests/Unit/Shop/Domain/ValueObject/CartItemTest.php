<?php

declare(strict_types=1);

namespace Tests\Unit\Shop\Domain\ValueObject;

use PHPUnit\Framework\TestCase;
use Src\Shop\Domain\Exceptions\MaxUnitPerProductDomainException;
use Tests\Unit\Shop\Domain\DataBuilders\CartItemsBuild;

/**
 * @group Domain
 */
class CartItemTest extends TestCase
{
    public function testCartItemMaxUnitPerProductDomainException()
    {
        CartItemsBuild::start(50);
        $this->expectException(MaxUnitPerProductDomainException::class);
        CartItemsBuild::start(51);
    }

}
