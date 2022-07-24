<?php

declare(strict_types=1);

namespace Tests\Unit\Shop\Domain\ValueObject;

use PHPUnit\Framework\TestCase;
use Src\Shop\Domain\ValueObject\Currency;

/**
 * @group Domain
 */
class CurrencyTest extends TestCase
{
    public function testCurrenciesDomainValueObject()
    {
        $euro = Currency::getEur();
        $dollar = Currency::getUsd();
        $this->assertEquals('EUR', $euro);
        $this->assertEquals('USD', $dollar);
    }

    public function testcheckCurrenciesDomainValueObject()
    {
        $euro = new Currency('EUR');
        $dollar = new Currency('USD');
        $this->assertTrue($euro->isEur());
        $this->assertTrue($dollar->isUsd());
        $this->assertFalse($euro->isUsd());
    }

    public function testDefaultCurrencyDomainValueObject()
    {
        $defaultCurrency = Currency::getDefaultValue();
        $this->assertEquals('EUR', $defaultCurrency);
    }
}
