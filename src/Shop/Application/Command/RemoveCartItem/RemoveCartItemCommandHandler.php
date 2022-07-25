<?php

declare(strict_types=1);

namespace Src\Shop\Application\Command\RemoveCartItem;

use Src\Shop\Domain\Contracts\IApplicationCommandHandler;
use Src\Shop\Domain\Contracts\ICartRepository;
use Src\Shop\Domain\ValueObject\CartId;
use Src\Shop\Domain\ValueObject\ProductId;

class RemoveCartItemCommandHandler implements IApplicationCommandHandler
{
    public function __construct(
        private ICartRepository $cartRepository
    ) {}

    public function handle(RemoveCartItemCommand $removeCartItemCommand): void
    {
        $cartId = new CartId($removeCartItemCommand->getCartId());
        $cart = $this->cartRepository->findOrFail($cartId);
        $cart->removeCartItem(
            new ProductId($removeCartItemCommand->getProductId())
        );
        $this->cartRepository->delete($cartId);
    }
}
