<?php

declare(strict_types=1);

namespace Tests\Feature\Shop\Domain\ValueObject;

use PHPUnit\Framework\TestCase;
use Src\Shared\Domain\Exceptions\InvalidInputDomainException;
use Src\Shop\Domain\ValueObject\Amount;

/**
 * @group Domain
 */
class AmountTest extends TestCase
{
    public function testNegativeAmountDomainValueObject()
    {
        $this->expectException(InvalidInputDomainException::class);
        new Amount(-10);
    }

    public function testWrongAmountDomainValueObject()
    {
        $this->expectError();
        new Amount('wrong-amount');
    }

    public function testAmountIsBiggerThanDomainValueObject()
    {
        $amountLower = new Amount(5);
        $amountGreater = new Amount(10);
        $this->assertTrue($amountGreater->isBiggerThan($amountLower));
    }
}
