<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Contracts;

use AlfredoMejia\CfdiPhp\Models\CFDIData;

/**
 * Interface para generadores de XML
 */
interface XMLGeneratorInterface
{
    /**
     * Genera el XML a partir de los datos del CFDI
     */
    public function generate(CFDIData $cfdiData): string;

    /**
     * Valida el XML generado contra el esquema XSD
     */
    public function validateXML(string $xml): bool;

    /**
     * Obtiene el namespace principal del XML
     */
    public function getNamespace(): string;

    /**
     * Obtiene la ubicación del esquema XSD
     */
    public function getSchemaLocation(): string;
}
