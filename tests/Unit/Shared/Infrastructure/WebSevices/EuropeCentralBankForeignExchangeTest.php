<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Infrastructure\WebSevices;

use PHPUnit\Framework\TestCase;
use Src\Shared\Infrastructure\WebServices\EuropeCentralBankForeignExchange;
use Src\Shop\Domain\ValueObject\Amount;
use Src\Shop\Domain\ValueObject\Currency;
use Src\Shop\Domain\ValueObject\Price;

/**
 * @group Infrastructure
 */
class EuropeCentralBankForeignExchangeTest extends TestCase
{
    public function testExternalAPIEuropeCentralBanckForeignExchange()
    {
        $externalApiUrl = env('WS_ECB_FOREIGN_EXCHANGE');
        $ecb = new EuropeCentralBankForeignExchange($externalApiUrl);
        $convertedPrice = $ecb->convertOrFail(new Price(
            new Currency('USD'),
            new Amount(2),
        ));
        $this->assertEquals('USD', $convertedPrice->getCurrency()->getValue());
    }
}
