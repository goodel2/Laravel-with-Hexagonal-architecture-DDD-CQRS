<?php

declare(strict_types=1);

namespace Src\Shop\Infrastructure\Repositories\Product;

use App\Models\Product as EloquentProductModel;
use Src\Shared\Domain\Utils;
use Src\Shop\Domain\Aggregate\Product;
use Src\Shop\Domain\Contracts\IProductRepository;
use Src\Shop\Domain\ValueObject\MinUnitsForDiscount;
use Src\Shop\Domain\ValueObject\ProductId;
use Src\Shop\Domain\ValueObject\ProductPrice;
use Src\Shop\Domain\ValueObject\UnitProductPrice;

final class EloquentProductRepository implements IProductRepository
{
    public function __construct(
        private EloquentProductModel $eloquentProductModel
    ) {}

    public function findOrFail(ProductId $productId): Product
    {
        $eloquentProduct = $this->eloquentProductModel->findOrFail($productId->getValue());
        $newProductId = Utils::generateUuid();

        return new Product(
            new ProductId($newProductId),
            new ProductPrice(
                new ProductId($newProductId),
                new UnitProductPrice($eloquentProduct->product_price),
                new UnitProductPrice($eloquentProduct->product_price_discounted),
                new MinUnitsForDiscount($eloquentProduct->min_units_discount)
            )
        );
    }

    public function save(Product $product): void
    {
        $newProduct = $this->eloquentProductModel;

        $data = [
            'product_price' => $product->getProductPrice()->getPriceWithNoDiscount()->getValue(),
            'product_price_discounted' => $product->getProductPrice()->getPriceWithDiscount()->getValue(),
            'min_units_discount' => $product->getProductPrice()->getMinUnitsForDiscount()->getValue(),
        ];

        $newProduct->create($data);
    }

    public function delete(ProductId $productId): void
    {
        $productToDelete = $this->eloquentProductModel->findOrFail($productId);
        $productToDelete->delete();
    }
}
