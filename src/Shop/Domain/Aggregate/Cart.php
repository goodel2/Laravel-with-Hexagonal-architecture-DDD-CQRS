<?php

declare(strict_types=1);

namespace Src\Shop\Domain\Aggregate;

use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use Src\Shared\Domain\Aggregate\AggregateRoot;
use Src\Shared\Infrastructure\Exceptions\EntityNotFoundInfrastructureException;
use Src\Shop\Domain\Collections\CartItemsCollection;
use Src\Shop\Domain\Contracts\IProductRepository;
use Src\Shop\Domain\ValueObject\Amount;
use Src\Shop\Domain\ValueObject\CartId;
use Src\Shop\Domain\ValueObject\CartItem;
use Src\Shop\Domain\ValueObject\Currency;
use Src\Shop\Domain\ValueObject\Price;
use Src\Shop\Domain\ValueObject\ProductId;
use Src\Shop\Domain\ValueObject\QuantityCartItem;

class Cart extends AggregateRoot
{
    private CartId $id;
    private CartItemsCollection $cartItems;

    public function __construct(CartId $id)
    {
        $this->id = $id;
        $this->cartItems = new CartItemsCollection([]);
    }

    public function getId(): CartId
    {
        return $this->id;
    }

    public function getCartItems(): array
    {
        return $this->getCartItemsCollection()->getItems();
    }

    public function getCartItemsCollection(): CartItemsCollection
    {
        return $this->cartItems;
    }

    public function addCartItem(CartItem $cartItem, IProductRepository $productRepository): void
    {
        $productRepository->findOrFail($cartItem->getProductId());
        $cartItemFound = $this->getCartItemByProductId($cartItem->getProductId());
        if ($cartItemFound) {
            $qtyCartItems = new QuantityCartItem(
                $cartItem->getQuantityCartItem()->getValue() +
                $cartItemFound->getQuantityCartItem()->getValue()
            );
        } else {
            $qtyCartItems = new QuantityCartItem($cartItem->getQuantityCartItem()->getValue());
        }

        $newCartItem = new CartItem($cartItem->getProductId(), $qtyCartItems);
        $this->getCartItemsCollection()->addItem($newCartItem);
    }

    public function removeCartItem(ProductId $productId): void
    {
        $cartItem = $this->getCartItemByProductId($productId);
        if (is_null($cartItem)) {
            $this->throwEntityNotFound($productId->getValue());
        } else {
            $this->getCartItemsCollection()->removeItem($cartItem);
        }
    }

    public function getTotalPriceInCart(IProductRepository $productRepository): Price
    {
        $totalPrice = 0;
        $cartItems = $this->getCartItems();
        foreach ($cartItems as $cartItem) {
            $product = $productRepository->findOrFail($cartItem->getProductId());
            $totalPrice += $product->getProductPrice()->getPrice()->getValue()
                * $cartItem->getQuantityCartItem()->getValue();
        }
        return new Price(
            new Currency(Currency::getDefaultValue()),
            new Amount($totalPrice)
        );
    }

    private function getCartItemByProductId(ProductId $productId): CartItem|null
    {
        $cartItems = $this->getCartItems();
        foreach ($cartItems as $cartItem) {
            if ($cartItem->getProductId()->getValue() === $productId->getValue()) {
                return $cartItem;
            }
        }
        return null;
    }

    private function throwEntityNotFound(string $productId): Throws
    {
        throw new EntityNotFoundInfrastructureException(
            sprintf("Cart item not found with product id: %s", $productId)
        );
    }
}
