<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Contracts;

use DOMDocument;
use DOMElement;

/**
 * Interface para estrategias de complementos
 * Implementa el patrón Strategy para manejar diferentes tipos de complementos
 */
interface ComplementStrategyInterface
{
    /**
     * Genera el XML del complemento
     */
    public function generateXML(DOMDocument $xml, DOMElement $complementoElement, object $complementData): void;

    /**
     * Valida los datos del complemento
     */
    public function validate(object $complementData): array;

    /**
     * Obtiene el namespace del complemento
     */
    public function getNamespace(): string;

    /**
     * Obtiene la ubicación del esquema XSD del complemento
     */
    public function getSchemaLocation(): string;

    /**
     * Obtiene el nombre del complemento
     */
    public function getComplementName(): string;
}
