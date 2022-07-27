<?php

declare(strict_types=1);

namespace Tests\Feature\Shop\Domain\DataBuilders;

use Src\Shared\Domain\ValueObject\Uuid;
use Src\Shop\Domain\Collections\CartItemsCollection;
use Src\Shop\Domain\ValueObject\CartItem;
use Src\Shop\Domain\ValueObject\ProductId;
use Src\Shop\Domain\ValueObject\QuantityCartItem;

class CartItemsCollectionBuild
{
    public static function start(int $total = 5, int $qtyCartItem = null): CartItemsCollection
    {
        if (is_null($qtyCartItem)) {
            $qtyCartItem = rand(1, 5);
        }

        $carItems = [];
        for ($i = 0; $i < $total; $i++) {
            $productId = new ProductId(Uuid::random()->getValue());
            $qty = new QuantityCartItem($qtyCartItem);
            $carItems[] = new CartItem($productId, $qty);
        }
        return new CartItemsCollection($carItems);
    }
}
