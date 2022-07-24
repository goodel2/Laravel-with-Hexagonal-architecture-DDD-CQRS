<?php

declare(strict_types=1);

namespace Tests\Unit\Shop\Domain\DataBuilders;

use Src\Shared\Domain\ValueObject\Uuid;
use Src\Shop\Domain\Collections\CartItemsCollection;
use Src\Shop\Domain\ValueObject\CartItem;
use Src\Shop\Domain\ValueObject\ProductId;
use Src\Shop\Domain\ValueObject\QuantityCartItem;

class CartItemsCollectionBuild
{
    public static function start($total = 5): CartItemsCollection
    {
        $carItems = [];
        for($i=0;$i<$total;$i++) {
            $productId = new ProductId(Uuid::random()->getValue());
            $qty = new QuantityCartItem(rand(1,2));
            $carItems[] = new CartItem($productId, $qty);
        }
        return new CartItemsCollection($carItems);
    }
}
