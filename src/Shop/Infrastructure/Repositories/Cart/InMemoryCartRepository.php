<?php

declare(strict_types=1);

namespace Src\Shop\Infrastructure\Repositories\Cart;

use Src\Shared\Infrastructure\Exceptions\EntityNotFoundInfrastructureException;
use Src\Shop\Domain\Aggregate\Cart;
use Src\Shop\Domain\Contracts\ICartRepository;
use Src\Shop\Domain\ValueObject\CartId;

class InMemoryCartRepository implements ICartRepository
{
    public function __construct(
        private array $carts = []
    ) {}

    public function findOrFail(CartId $cartId): Cart
    {
        if (isset($this->carts[$cartId->getValue()])) {
            return $this->carts[$cartId->getValue()];
        }
        throw new EntityNotFoundInfrastructureException(
            sprintf('Cart not found in our repository: %s', $cartId->getValue())
        );
    }

    public function save(Cart $cart): void
    {
        $this->carts[$cart->getId()->getValue()] = $cart;
    }

    public function delete(CartId $cartId): void
    {
        if (isset($this->carts[$cartId->getValue()])) {
            unset($this->carts[$cartId->getValue()]);
        }
        throw new EntityNotFoundInfrastructureException(
            sprintf('Cart not found to delete in our repository: %s', $cartId->getValue())
        );
    }
}
