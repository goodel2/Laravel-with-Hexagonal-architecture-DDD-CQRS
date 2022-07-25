<?php

declare(strict_types=1);

namespace Src\Shop\Domain\Collections;

use Src\Shared\Domain\Collections\Collection;
use Src\Shop\Domain\Exceptions\CartItemNotExistsToRemoveItemDomainException;
use Src\Shop\Domain\ValueObject\CartItem;

class CartItemsCollection extends Collection
{
    public function __construct(
        private array $items
    ) {
        parent::__construct($items);
    }

    public function getType(): string
    {
        return CartItem::class;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotalNumberItems(): int
    {
        return count($this->items);
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
        throw new CartItemNotExistsToRemoveItemDomainException(
            sprintf('You cannot remove a cart item than does not exists.')
        );
    }
}
