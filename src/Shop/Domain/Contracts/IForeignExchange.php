<?php

declare(strict_types=1);

namespace Src\Shop\Domain\Contracts;

use Src\Shop\Domain\ValueObject\Price;

interface IForeignExchange
{
    public function convertOrFail(Price $price): Price;
}
