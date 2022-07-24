<?php

declare(strict_types=1);

namespace Src\Shared\Domain\ValueObject;

abstract class ValueObject
{
    public function equals(ValueObject|null $o): bool
    {
        if ($o === null) {
            return false;
        }
        return get_class($this) === get_class($o) && $this->equalValues($o);
    }

    abstract function getValue(): mixed;

    abstract protected function equalValues(ValueObject $o): bool;
}
