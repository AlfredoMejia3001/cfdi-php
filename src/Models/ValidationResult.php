<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Models;

/**
 * Modelo que encapsula el resultado de una validación
 */
class ValidationResult
{
    private bool $valid;
    private array $errors;
    private array $data;
    private string $message;

    public function __construct(bool $valid = true, array $errors = [], array $data = [], string $message = '')
    {
        $this->valid = $valid;
        $this->errors = $errors;
        $this->data = $data;
        $this->message = $message;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function addError(string $error): self
    {
        $this->errors[] = $error;
        $this->valid = false;
        return $this;
    }

    public function setData(string $key, $value): self
    {
        $this->data[$key] = $value;
        return $this;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}
