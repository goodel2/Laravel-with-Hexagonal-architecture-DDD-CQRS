<?php

declare(strict_types=1);

namespace Tests\Unit\Shop\Domain\Collections;

use PHPUnit\Framework\TestCase;
use Src\Shared\Domain\ValueObject\Uuid;
use Src\Shop\Domain\Exceptions\CartItemNotExistsToRemoveItemDomainException;
use Src\Shop\Domain\Exceptions\MaxDifferentProductsInCartDomainException;
use Src\Shop\Domain\ValueObject\CartItem;
use Src\Shop\Domain\ValueObject\ProductId;
use Src\Shop\Domain\ValueObject\QuantityCartItem;
use Tests\Unit\Shop\Domain\DataBuilders\CartItemsCollectionBuild;

/**
 * @group Domain
 */
class CartItemsCollectionTest extends TestCase
{
    public function testAddCartItemsCollectionDomainCollection()
    {
        $cartItemsCollection = CartItemsCollectionBuild::start();
        $cartItem = new CartItem(
            new ProductId(Uuid::random()->getValue()),
            new QuantityCartItem(rand(1, 2))
        );

        $this->assertTrue($cartItemsCollection->addItem($cartItem));
        $this->assertFalse($cartItemsCollection->addItem($cartItem));
    }

    public function testFindCartItemsCollectionDomainCollection()
    {
        $cartItemsCollection = CartItemsCollectionBuild::start();
        $cartItem = last($cartItemsCollection->getItems());
        $newCartItem = new CartItem(
            new ProductId(Uuid::random()->getValue()),
            new QuantityCartItem(rand(1, 2))
        );

        $this->assertNotNull($cartItemsCollection->findItem($cartItem));
        $this->assertNull($cartItemsCollection->findItem($newCartItem));
    }

    public function testRemoveCartItemsCollectionDomainCollection()
    {
        $cartItemsCollection = CartItemsCollectionBuild::start();
        $cartItem = last($cartItemsCollection->getItems());
        $newCartItem = new CartItem(
            new ProductId(Uuid::random()->getValue()),
            new QuantityCartItem(rand(1, 2))
        );

        $this->assertTrue($cartItemsCollection->removeItem($cartItem));

        $this->expectException(CartItemNotExistsToRemoveItemDomainException::class);
        $cartItemsCollection->removeItem($newCartItem);
    }

    public function testConstructorMaxDifferentProductsInCartItemsCollectionDomainCollection()
    {
        $this->expectException(MaxDifferentProductsInCartDomainException::class);
        CartItemsCollectionBuild::start(11);
    }

    public function testAddItemMaxDifferentProductsInCartItemsCollectionDomainCollection()
    {
        $this->expectException(MaxDifferentProductsInCartDomainException::class);
        $cartItemsCollection = CartItemsCollectionBuild::start(9);
        $cartItemsCollectionSecond = CartItemsCollectionBuild::start(2);
        foreach ($cartItemsCollectionSecond->getItems() as $item) {
            $cartItemsCollection->addItem($item);
        }
    }
}
