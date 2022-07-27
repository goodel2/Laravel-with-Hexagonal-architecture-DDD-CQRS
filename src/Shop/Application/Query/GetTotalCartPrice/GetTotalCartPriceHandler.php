<?php

declare(strict_types=1);

namespace Src\Shop\Application\Query\GetTotalCartPrice;

use Src\Shop\Domain\Contracts\IApplicationQueryHandler;
use Src\Shop\Domain\Contracts\ICartRepository;
use Src\Shop\Domain\Contracts\IProductRepository;
use Src\Shop\Domain\ValueObject\CartId;
use Src\Shop\Domain\ValueObject\Currency;

class GetTotalCartPriceHandler implements IApplicationQueryHandler
{
    public function __construct(
        private ICartRepository    $cartRepository,
        private IProductRepository $productRepository,
    ) {}

    public function handle(GetTotalCartPriceQuery $totalCartPrice): GetTotalCartPriceDTO
    {
        $currency = new Currency($totalCartPrice->getCurrency());
        $cart = $this->cartRepository->findOrFail(new CartId($totalCartPrice->getCartId()));

        $priceWithDiscount = $cart->getTotalPriceInCart($this->productRepository, true)
            ->getValue();

        $priceWithNoDiscount = $cart->getTotalPriceInCart($this->productRepository, false)
            ->getValue();

        return new GetTotalCartPriceDTO(
            $currency->getValue(),
            $priceWithDiscount,
            $priceWithNoDiscount,
        );
    }
}
