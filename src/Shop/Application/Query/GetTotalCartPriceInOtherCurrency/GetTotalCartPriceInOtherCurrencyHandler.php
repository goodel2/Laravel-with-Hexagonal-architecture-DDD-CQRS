<?php

declare(strict_types=1);

namespace Src\Shop\Application\Query\GetTotalCartPriceInOtherCurrency;

use Src\Shop\Domain\Contracts\IApplicationQueryHandler;
use Src\Shop\Domain\Contracts\ICartRepository;
use Src\Shop\Domain\Contracts\IForeignExchange;
use Src\Shop\Domain\Contracts\IProductRepository;
use Src\Shop\Domain\ValueObject\Amount;
use Src\Shop\Domain\ValueObject\CartId;
use Src\Shop\Domain\ValueObject\Currency;
use Src\Shop\Domain\ValueObject\Price;

class GetTotalCartPriceInOtherCurrencyHandler implements IApplicationQueryHandler
{
    public function __construct(
        private ICartRepository    $cartRepository,
        private IProductRepository $productRepository,
        private IForeignExchange   $foreignExchange
    ) {}

    public function handle(GetTotalCartPriceInOtherCurrencyQuery $totalCartPrice): GetTotalCartPriceInOtherCurrencyDTO
    {
        $currency = new Currency($totalCartPrice->getCurrency());
        $cart = $this->cartRepository->findOrFail(new CartId($totalCartPrice->getCartId()));

        if ($totalCartPrice->getCurrency() !== Currency::getDefaultValue()) {

            $priceWithDiscount = $this->foreignExchange->convertOrFail(new Price(
                    new Currency($totalCartPrice->getCurrency()),
                    new Amount($cart->getTotalPriceInCart($this->productRepository, true)->getValue())
                )
            )->getValue();

            $priceWithNoDiscount = $this->foreignExchange->convertOrFail(new Price(
                    new Currency($totalCartPrice->getCurrency()),
                    new Amount($cart->getTotalPriceInCart($this->productRepository, false)->getValue())
                )
            )->getValue();

        } else {
            $priceWithDiscount = $cart->getTotalPriceInCart($this->productRepository, true)->getValue();
            $priceWithNoDiscount = $cart->getTotalPriceInCart($this->productRepository, false)->getValue();
        }

        return new GetTotalCartPriceInOtherCurrencyDTO(
            $currency->getValue(),
            $priceWithDiscount,
            $priceWithNoDiscount,
        );
    }
}
