<?php

declare(strict_types=1);

namespace Tests\Unit\Shop\Domain\DataBuilders;

use Src\Shared\Domain\ValueObject\Uuid;
use Src\Shop\Domain\ValueObject\CartItem;
use Src\Shop\Domain\ValueObject\ProductId;
use Src\Shop\Domain\ValueObject\QuantityCartItem;

class CartItemsBuild
{
    public static function start($totalItems = 10): CartItem
    {
        $productId = new ProductId(Uuid::random()->getValue());
        $qty = new QuantityCartItem($totalItems);
        return new CartItem($productId, $qty);
    }
}
