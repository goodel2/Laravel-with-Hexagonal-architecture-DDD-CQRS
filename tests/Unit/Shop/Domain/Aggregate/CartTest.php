<?php

declare(strict_types=1);

namespace Tests\Unit\Shop\Domain\Aggregate;

use PHPUnit\Framework\TestCase;
use Src\Shared\Domain\Utils;
use Src\Shared\Infrastructure\Exceptions\EntityNotFoundInfrastructureException;
use Src\Shop\Domain\Aggregate\Cart;
use Src\Shop\Domain\Aggregate\Product;
use Src\Shop\Domain\Collections\CartItemsCollection;
use Src\Shop\Domain\Exceptions\MaxDifferentProductsInCartDomainException;
use Src\Shop\Domain\Exceptions\MaxUnitPerProductDomainException;
use Src\Shop\Domain\ValueObject\CartId;
use Src\Shop\Domain\ValueObject\MinUnitsForDiscount;
use Src\Shop\Domain\ValueObject\ProductPrice;
use Src\Shop\Domain\ValueObject\UnitProductPrice;
use Src\Shop\Infrastructure\Repositories\InMemoryProductRepository;
use Tests\Unit\Shop\Domain\DataBuilders\CartItemsCollectionBuild;

/**
 * @group Domain
 */
class CartTest extends TestCase
{
    public function testMaxDifferentProductsInCartAggregate()
    {
        $cartId = new CartId(Utils::generateUuid());
        $cartItemsCollection = cartItemsCollectionBuild::start(11);
        $this->expectException(MaxDifferentProductsInCartDomainException::class);
        new Cart($cartId, $cartItemsCollection);
    }

    public function testMaxUnitPerProductInCartDomainAggregate()
    {
        $cartId = new CartId(Utils::generateUuid());
        $cartItemsCollection = cartItemsCollectionBuild::start(1, 51);
        $this->expectException(MaxUnitPerProductDomainException::class);
        new Cart($cartId, $cartItemsCollection);
    }

    public function testCartItemByProductIdDomainAggregate()
    {
        $cartId = new CartId(Utils::generateUuid());
        $cart = new Cart($cartId, new CartItemsCollection([]));

        $cartItemsCollectionSecond = cartItemsCollectionBuild::start(6);
        $productRepository = new InMemoryProductRepository();
        $cartItems = $cartItemsCollectionSecond->getItems();

        foreach ($cartItems as $cartItem) {
            $product = new Product(
                $cartItem->getProductId(),
                new ProductPrice(
                    $cartItem->getProductId(),
                    new UnitProductPrice(rand(1,5)),
                    new UnitProductPrice(rand(1,5)),
                    new MinUnitsForDiscount(1)
                )
            );
            $productRepository->save($product);
            $cart->addCartItem($cartItem, $productRepository);
        }

        $cart->removeCartItem($cartItem->getProductId());
        $this->assertEquals(5, count($cart->getCartItems()));
        $this->assertGreaterThan(0, $cart->getTotalPriceInCart($productRepository)->getValue());
        $this->expectException(EntityNotFoundInfrastructureException::class);
        $cart->addCartItem(last($cart->getCartItems()), new InMemoryProductRepository([]));
    }
}
