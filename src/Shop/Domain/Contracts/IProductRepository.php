<?php

declare(strict_types=1);

namespace Src\Shop\Domain\Contracts;

use Src\Shop\Domain\Aggregate\Product;
use Src\Shop\Domain\ValueObject\ProductId;

interface IProductRepository
{
    public function findOrFail(ProductId $productId): Product;

    public function save(Product $product): void;

    public function delete(ProductId $productId): void;
}
