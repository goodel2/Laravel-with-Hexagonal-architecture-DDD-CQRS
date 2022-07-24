<?php

declare(strict_types=1);

namespace Src\Shared\Infrastructure\Exceptions;

use Exception;

class InfrastructureException extends Exception
{
    public function __construct(string $errorMessage)
    {
        parent::__construct($errorMessage);
    }
}
