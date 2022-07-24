<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Exceptions;

use DomainException as BaseDomainException;

class DomainException extends BaseDomainException
{
    public function __construct(string $errorMessage)
    {
        parent::__construct($errorMessage);
    }
}
