<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Contracts;

/**
 * Interface para servicios de comunicación con Finkok
 */
interface FinkokServiceInterface
{
    /**
     * Valida las credenciales usando el servicio de utilities
     */
    public function validateCredentials(string $username, string $password): array;

    /**
     * Obtiene información de un RFC usando el servicio de registro
     */
    public function getRFCInfo(string $username, string $password, string $rfc): array;

    /**
     * Realiza una llamada SOAP genérica a los servicios de Finkok
     */
    public function soapCall(string $wsdlUrl, string $method, array $params): array;
}
