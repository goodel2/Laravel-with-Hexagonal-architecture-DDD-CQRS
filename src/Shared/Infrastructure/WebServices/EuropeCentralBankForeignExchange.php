<?php

declare(strict_types=1);

namespace Src\Shared\Infrastructure\WebServices;

use Src\Shared\Infrastructure\Exceptions\InvalidOutputInfrastructureException;
use Src\Shared\Infrastructure\Utils;
use Src\Shop\Domain\Contracts\IForeignExchange;
use Src\Shop\Domain\ValueObject\Amount;
use Src\Shop\Domain\ValueObject\Currency;
use Src\Shop\Domain\ValueObject\Price;
use Src\Shop\Infrastructure\Exceptions\CurrencyNotFoundForeignExchangeInfrastructureException;

class EuropeCentralBankForeignExchange implements IForeignExchange
{
    public function __construct(
        private string $externalApiUrl
    ) {}

    private function getExternalApiUrl(): string
    {
        return $this->externalApiUrl;
    }

    public function convertOrFail(Price $price): Price
    {
        $url = $this->getExternalApiUrl();
        $outputHtml = Utils::getCurlXmlFromUrl($url);

        if (!is_string($outputHtml)) {
            throw new InvalidOutputInfrastructureException("Invalid output");
        }

        $outputXml = simplexml_load_string($outputHtml);

        $currenciesRate = $outputXml->Cube->Cube->Cube;
        foreach ($currenciesRate as $rate) {
            if (((string) $rate['currency']) === $price->getCurrency()->getValue()) {
                $convertedPrice = new Price(
                    new Currency($price->getCurrency()->getValue()),
                    new Amount($price->getAmount()->getValue() * (float) $rate['rate']),
                );
                return $convertedPrice;
            }
        }

        throw new CurrencyNotFoundForeignExchangeInfrastructureException("Currency not found");
    }
}
