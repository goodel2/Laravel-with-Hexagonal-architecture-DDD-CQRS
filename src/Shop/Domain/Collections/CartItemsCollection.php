<?php

declare(strict_types=1);

namespace Src\Shop\Domain\Collections;

use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use Src\Shared\Domain\Collections\Collection;
use Src\Shop\Domain\Exceptions\CartItemNotExistsToRemoveItemDomainException;
use Src\Shop\Domain\Exceptions\MaxDifferentProductsInCartDomainException;
use Src\Shop\Domain\ValueObject\CartItem;

class CartItemsCollection extends Collection
{
    private array $items;

    private const MAX_DIFFERENT_PRODUCTS = 10;

    public function __construct(
        array $items
    ) {
        parent::__construct($items);
        $this->validateMaxDifferentProducts(count($items));
        $this->items = $items;
    }

    public function getType(): string
    {
        return CartItem::class;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function findItem(CartItem $newItem): CartItem|null
    {
        $itemFound = null;
        $items = $this->items;
        foreach ($items as $item) {
            if ($item->equalValues($newItem)) {
                $itemFound = $item;
            }
        }
        return $itemFound;
    }

    public function addItem(CartItem $newItem): bool
    {
        $this->validateMaxDifferentProducts(count($this->getItems()) + 1);
        $cartItemFound = $this->findItem($newItem);
        if (is_null($cartItemFound)) {
            $this->items[] = $newItem;
            return true;
        }
        return false;
    }

    public function removeItem(CartItem $newItem): bool
    {
        $items = $this->items;
        foreach ($items as $k => $item) {
            if ($item->equalValues($newItem)) {
                unset($this->items[$k]);
                return true;
            }
        }
        $this->throwCartItemNotExistsToRemoveItemDomainException();
        return false;
    }

    public static function getMaxDifferentProducts(): mixed
    {
        return self::MAX_DIFFERENT_PRODUCTS;
    }

    private function validateMaxDifferentProducts(int $totalItems): void
    {
        $maxDifferentProducts = self::getMaxDifferentProducts();
        $differentProductsLeft = $maxDifferentProducts - $totalItems;
        if ($differentProductsLeft < 0) {
            $this->throwDifferentProductsLeft($maxDifferentProducts);
        }
    }

    private function throwDifferentProductsLeft(int $maxDifferentProducts): Throws
    {
        throw new MaxDifferentProductsInCartDomainException(
            sprintf('You have got the maximum different products in cart: %s', $maxDifferentProducts)
        );
    }

    private function throwCartItemNotExistsToRemoveItemDomainException(): Throws
    {
        throw new CartItemNotExistsToRemoveItemDomainException(
            sprintf('You cannot remove a cart item than does not exists.')
        );
    }

}
