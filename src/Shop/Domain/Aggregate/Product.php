<?php

declare(strict_types=1);

namespace Src\Shop\Domain\Aggregate;

use Src\Shared\Domain\Aggregate\AggregateRoot;
use Src\Shop\Domain\ValueObject\ProductId;
use Src\Shop\Domain\ValueObject\ProductPrice;

class Product extends AggregateRoot
{
    public function __construct(
        private ProductId    $id,
        private ProductPrice $productPrice,
    ) {}

    public function getId(): ProductId
    {
        return $this->id;
    }

    public function getProductPrice(): ProductPrice
    {
        return $this->productPrice;
    }
}
