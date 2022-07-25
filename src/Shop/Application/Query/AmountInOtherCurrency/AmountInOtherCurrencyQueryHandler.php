<?php

declare(strict_types=1);

namespace Src\Shop\Application\Query\AmountInOtherCurrency;

use Src\Shop\Domain\Contracts\IApplicationQueryHandler;
use Src\Shop\Domain\Contracts\IForeignExchange;
use Src\Shop\Domain\ValueObject\Amount;
use Src\Shop\Domain\ValueObject\Currency;
use Src\Shop\Domain\ValueObject\Price;

class AmountInOtherCurrencyQueryHandler implements IApplicationQueryHandler
{
    public function __construct(
        private IForeignExchange $foreignExchange
    ) {}

    public function handle(AmountInOtherCurrencyQuery $totalCartPriceQuery): AmountInOtherCurrencyDTO
    {
        $newCurrency = $oldCurrency = $totalCartPriceQuery->getCurrency();
        $newAmount = $oldAmount = $totalCartPriceQuery->getAmount();

        if ($totalCartPriceQuery->getCurrency() !== Currency::getEur()) {
            $newPrice = $this->foreignExchange->convertOrFail(
                new Price(
                    new Currency($totalCartPriceQuery->getCurrency()),
                    new Amount($totalCartPriceQuery->getAmount())
                )
            );
            $newAmount = $newPrice->getAmount()->getValue();
            $newCurrency = $newPrice->getCurrency()->getValue();
        }

        return new AmountInOtherCurrencyDTO(
            $newCurrency, $newAmount, $oldCurrency, $oldAmount
        );
    }
}
