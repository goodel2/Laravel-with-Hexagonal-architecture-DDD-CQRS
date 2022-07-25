<?php

declare(strict_types=1);

namespace Src\Shop\Application\Command\AddCartItem;

use Src\Shop\Domain\Contracts\IApplicationCommandHandler;
use Src\Shop\Domain\Contracts\ICartRepository;
use Src\Shop\Domain\Contracts\IProductRepository;
use Src\Shop\Domain\ValueObject\CartId;
use Src\Shop\Domain\ValueObject\CartItem;
use Src\Shop\Domain\ValueObject\ProductId;
use Src\Shop\Domain\ValueObject\QuantityCartItem;

class AddCartItemCommandHandler implements IApplicationCommandHandler
{
    public function __construct(
        private ICartRepository $cartRepository,
        private IProductRepository $productRepository
    ) {}

    public function handle(AddCartItemCommand $addCartItemCommand): void
    {
        $cartId = new CartId($addCartItemCommand->getCartId());
        $cart = $this->cartRepository->findOrFail($cartId);
        $cart->addCartItem(
            new CartItem(
                new ProductId($addCartItemCommand->getProductId()),
                new QuantityCartItem($addCartItemCommand->getQuantityCartItem())
            ),
            $this->productRepository
        );
        $this->cartRepository->save($cart);
    }
}
