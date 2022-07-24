<?php

declare(strict_types=1);

namespace Src\Shop\Domain\Contracts;

use Src\Shop\Domain\Aggregate\Cart;
use Src\Shop\Domain\ValueObject\CartId;

interface ICartRepository
{
    public function findOrFail(CartId $id): Cart;

    public function save(Cart $cart): void;
}
