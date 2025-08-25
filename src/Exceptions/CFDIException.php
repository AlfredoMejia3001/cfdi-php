<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Exceptions;

use Exception;

/**
 * Excepción base para todas las excepciones del CFDI
 */
class CFDIException extends Exception
{
    private array $errors = [];

    public function __construct(string $message = '', array $errors = [], int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}
