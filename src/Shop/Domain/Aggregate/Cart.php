<?php

declare(strict_types=1);

namespace Src\Shop\Domain\Aggregate;

use Src\Shared\Domain\Aggregate\AggregateRoot;
use Src\Shared\Infrastructure\Exceptions\EntityNotFoundInfrastructureException;
use Src\Shop\Domain\Collections\CartItemsCollection;
use Src\Shop\Domain\Contracts\IProductRepository;
use Src\Shop\Domain\Exceptions\MaxDifferentProductsInCartDomainException;
use Src\Shop\Domain\Exceptions\MaxUnitPerProductDomainException;
use Src\Shop\Domain\ValueObject\Amount;
use Src\Shop\Domain\ValueObject\CartId;
use Src\Shop\Domain\ValueObject\CartItem;
use Src\Shop\Domain\ValueObject\Currency;
use Src\Shop\Domain\ValueObject\Price;
use Src\Shop\Domain\ValueObject\ProductId;
use Src\Shop\Domain\ValueObject\QuantityCartItem;

class Cart extends AggregateRoot
{
    private CartId $cartId;
    private cartItemsCollection $cartItems;

    private const MAX_UNITS_PER_PRODUCT = 50;
    private const MAX_DIFFERENT_PRODUCTS = 10;

    public function __construct(CartId $cartId)
    {
        $this->cartId = $cartId;
        $this->cartItems = new CartItemsCollection([]);
    }

    public function getId(): CartId
    {
        return $this->cartId;
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
        $this->validateMaxDifferentProducts($this->cartItems->getTotalNumberItems() + 1);
        $this->validateMaxUnitsPerProductByCartItem($cartItem);

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
            throw new EntityNotFoundInfrastructureException(
                sprintf("Cart item not found with product id: %s", $productId->getValue())
            );
        } else {
            $this->getCartItemsCollection()->removeItem($cartItem);
        }
    }

    public function getTotalPriceInCart(IProductRepository $productRepository, bool $priceWithDiscount = true): Price
    {
        $totalPrice = 0;
        $cartItems = $this->getCartItems();
        foreach ($cartItems as $cartItem) {
            $product = $productRepository->findOrFail($cartItem->getProductId());
            $productPrice = $priceWithDiscount ?
                $product->getProductPrice()->getPriceWithDiscount()->getValue() :
                $product->getProductPrice()->getPriceWithNoDiscount()->getValue();
            $quantityCartItems = $cartItem->getQuantityCartItem()->getValue();
            $totalPrice += $productPrice * $quantityCartItems;
        }
        return new Price(
            new Currency(Currency::getDefaultValue()),
            new Amount($totalPrice)
        );
    }

    public static function getMaxDifferentProducts(): mixed
    {
        return self::MAX_DIFFERENT_PRODUCTS;
    }

    public static function getMaxUnitsPerProduct(): mixed
    {
        return self::MAX_UNITS_PER_PRODUCT;
    }

    private function getCartItemByProductId(ProductId $productId): ?CartItem
    {
        $cartItems = $this->getCartItems();
        foreach ($cartItems as $cartItem) {
            if ($cartItem->getProductId()->getValue() === $productId->getValue()) {
                return $cartItem;
            }
        }
        return null;
    }

    private function validateMaxUnitsPerProductByCartItem(CartItem $cartItem): void
    {
        $maxUnitsPerProduct = self::getMaxUnitsPerProduct();
        $cartItemsLeft = $maxUnitsPerProduct - $cartItem->getQuantityCartItem()->getValue();
        if ($cartItemsLeft < 0) {
            throw new MaxUnitPerProductDomainException(
                sprintf('You have got the maximum number of items in cart: %s', $maxUnitsPerProduct)
            );
        }
    }

    private function validateMaxDifferentProducts(int $totalItems): void
    {
        $maxDifferentProducts = self::getMaxDifferentProducts();
        $differentProductsLeft = $maxDifferentProducts - $totalItems;
        if ($differentProductsLeft < 0) {
            throw new MaxDifferentProductsInCartDomainException(
                sprintf('You have got the maximum different products in cart: %s', $maxDifferentProducts)
            );
        }
    }
}
