<?php

declare(strict_types=1);

namespace Src\Shop\Infrastructure;

use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use Src\Shared\Infrastructure\Exceptions\EntityNotFoundInfrastructureException;
use Src\Shop\Domain\Aggregate\Product;
use Src\Shop\Domain\Contracts\IProductRepository;
use Src\Shop\Domain\ValueObject\ProductId;

class InMemoryProductRepository implements IProductRepository
{
    public function __construct(
        private array $products = []
    ) {}

    public function findOrFail(ProductId $productId): Product
    {
        if (isset($this->products[$productId->getValue()])) {
            return $this->products[$productId->getValue()];
        }
        $this->EntityNotFoundException($productId->getValue());
    }

    public function save(Product $product): void
    {
        $this->products[$product->getId()->getValue()] = $product;
    }

    private function EntityNotFoundException(string $productId): Throws
    {
        throw new EntityNotFoundInfrastructureException(
            sprintf('Product not found: %s', $productId)
        );
    }
}
