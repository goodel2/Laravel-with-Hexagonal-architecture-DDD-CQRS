<?php

declare(strict_types=1);

namespace Tests\Feature\Shop\Domain\Collections;

use PHPUnit\Framework\TestCase;
use Src\Shared\Domain\ValueObject\Uuid;
use Src\Shop\Domain\Exceptions\CartItemNotExistsToRemoveItemDomainException;
use Src\Shop\Domain\Exceptions\MaxDifferentProductsInCartDomainException;
use Src\Shop\Domain\ValueObject\CartItem;
use Src\Shop\Domain\ValueObject\ProductId;
use Src\Shop\Domain\ValueObject\QuantityCartItem;
use Tests\Feature\Shop\Domain\DataBuilders\CartItemsCollectionBuild;

/**
 * @group Domain
 */
class CartItemsCollectionTest extends TestCase
{
    public function testAddCartItemsCollectionDomainCollection()
    {
        $cartItemsCollection = CartItemsCollectionBuild::start(10);
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
        $cartItemsCollection = CartItemsCollectionBuild::start(5);
        $cartItem = last($cartItemsCollection->getItems());
        $newCartItem = new CartItem(
            new ProductId(Uuid::random()->getValue()),
            new QuantityCartItem(rand(1, 2))
        );

        $this->assertTrue($cartItemsCollection->removeItem($cartItem));
        $this->expectException(CartItemNotExistsToRemoveItemDomainException::class);
        $cartItemsCollection->removeItem($newCartItem);
    }

    public function testBuildingManyCartItemsCollectionDomainCollection()
    {
        $cartItemsCollection = CartItemsCollectionBuild::start(100);
        $cartItemsCollectionSecond = CartItemsCollectionBuild::start(20);
        foreach ($cartItemsCollectionSecond->getItems() as $item) {
            $cartItemsCollection->addItem($item);
        }
        $this->assertEquals(120, $cartItemsCollection->getTotalNumberItems());
    }
}
