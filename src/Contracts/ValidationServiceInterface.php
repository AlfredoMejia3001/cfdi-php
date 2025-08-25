<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Contracts;

use AlfredoMejia\CfdiPhp\Models\ValidationResult;

/**
 * Interface para servicios de validación
 */
interface ValidationServiceInterface
{
    /**
     * Valida las credenciales de Finkok
     */
    public function validateCredentials(string $username, string $password): ValidationResult;

    /**
     * Valida el estado de un RFC en Finkok
     */
    public function validateRFC(string $username, string $password, string $rfc): ValidationResult;

    /**
     * Valida los datos de un CFDI antes de la generación
     */
    public function validateCFDIData(object $cfdiData): ValidationResult;
}
