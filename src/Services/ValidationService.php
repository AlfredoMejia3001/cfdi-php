<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Services;

use AlfredoMejia\CfdiPhp\Contracts\ValidationServiceInterface;
use AlfredoMejia\CfdiPhp\Contracts\FinkokServiceInterface;
use AlfredoMejia\CfdiPhp\Models\ValidationResult;
use AlfredoMejia\CfdiPhp\Models\CFDIData;

/**
 * Servicio de validación para datos de CFDI y servicios de Finkok
 */
class ValidationService implements ValidationServiceInterface
{
    private FinkokServiceInterface $finkokService;

    public function __construct(FinkokServiceInterface $finkokService)
    {
        $this->finkokService = $finkokService;
    }

    /**
     * {@inheritdoc}
     */
    public function validateCredentials(string $username, string $password): ValidationResult
    {
        if (empty(trim($username)) || empty(trim($password))) {
            return new ValidationResult(
                false,
                ['Se requieren tanto el usuario como la contraseña de Finkok'],
                [],
                'Credenciales faltantes'
            );
        }

        try {
            $result = $this->finkokService->validateCredentials($username, $password);
            
            if ($result['valid'] !== true) {
                return new ValidationResult(
                    false,
                    [$result['message'] ?? 'Error desconocido al validar las credenciales'],
                    $result,
                    'Error de autenticación'
                );
            }

            return new ValidationResult(true, [], $result, 'Credenciales válidas');
            
        } catch (\Exception $e) {
            return new ValidationResult(
                false,
                ['Error al validar credenciales: ' . $e->getMessage()],
                [],
                'Error de validación'
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validateRFC(string $username, string $password, string $rfc): ValidationResult
    {
        if (empty(trim($rfc))) {
            return new ValidationResult(
                false,
                ['El RFC es requerido'],
                [],
                'RFC faltante'
            );
        }

        try {
            $result = $this->finkokService->getRFCInfo($username, $password, $rfc);
            
            if (!isset($result['success']) || $result['success'] !== true) {
                return new ValidationResult(
                    false,
                    [$result['message'] ?? 'Error desconocido al validar el RFC'],
                    $result,
                    'Error de validación de RFC'
                );
            }

            // Verificar el estado del RFC
            if (isset($result['status'])) {
                $status = strtoupper($result['status']);
                
                if ($status === 'I') {
                    return new ValidationResult(
                        false,
                        ['El RFC emisor se encuentra inactivo en la cuenta'],
                        $result,
                        'RFC inactivo'
                    );
                }
                
                if ($status !== 'A') {
                    return new ValidationResult(
                        false,
                        ['El estado del RFC no es válido (Estado: ' . $status . ')'],
                        $result,
                        'Estado de RFC inválido'
                    );
                }
            } else {
                return new ValidationResult(
                    false,
                    ['No se pudo determinar el estado del RFC'],
                    $result,
                    'Estado de RFC indeterminado'
                );
            }

            return new ValidationResult(true, [], $result, 'RFC válido y activo');
            
        } catch (\Exception $e) {
            return new ValidationResult(
                false,
                ['Error al validar RFC: ' . $e->getMessage()],
                [],
                'Error de validación'
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validateCFDIData(object $cfdiData): ValidationResult
    {
        $errors = [];

        if (!$cfdiData instanceof CFDIData) {
            return new ValidationResult(
                false,
                ['Los datos proporcionados no son válidos'],
                [],
                'Datos inválidos'
            );
        }

        // Validar comprobante
        $comprobante = $cfdiData->getComprobante();
        if (empty($comprobante->getNoCertificado())) {
            $errors[] = 'El número de certificado es requerido';
        }

        if (empty($comprobante->getSubTotal())) {
            $errors[] = 'El subtotal es requerido';
        }

        if (empty($comprobante->getTotal())) {
            $errors[] = 'El total es requerido';
        }

        // Validar emisor
        $emisor = $cfdiData->getEmisor();
        if (empty($emisor->getRfc())) {
            $errors[] = 'El RFC del emisor es requerido';
        }

        if (empty($emisor->getNombre())) {
            $errors[] = 'El nombre del emisor es requerido';
        }

        if (empty($emisor->getRegimenFiscal())) {
            $errors[] = 'El régimen fiscal del emisor es requerido';
        }

        // Validar receptor
        $receptor = $cfdiData->getReceptor();
        if (empty($receptor->getRfc())) {
            $errors[] = 'El RFC del receptor es requerido';
        }

        if (empty($receptor->getNombre())) {
            $errors[] = 'El nombre del receptor es requerido';
        }

        // Validar conceptos (excepto para CFDI de Pagos)
        $conceptos = $cfdiData->getConceptos();
        $tipoComprobante = $cfdiData->getComprobante()->getTipoDeComprobante();
        
        if (empty($conceptos) && $tipoComprobante !== 'P') {
            $errors[] = 'Se requiere al menos un concepto';
        } elseif (!empty($conceptos)) {
            foreach ($conceptos as $index => $concepto) {
                if (empty($concepto->getClaveProdServ())) {
                    $errors[] = "La clave del producto/servicio es requerida en el concepto " . ($index + 1);
                }
                
                if (empty($concepto->getDescripcion())) {
                    $errors[] = "La descripción es requerida en el concepto " . ($index + 1);
                }
                
                if (empty($concepto->getCantidad()) || $concepto->getCantidad() <= 0) {
                    $errors[] = "La cantidad debe ser mayor a cero en el concepto " . ($index + 1);
                }
                
                // Para traslados y pagos, el importe puede ser 0
                $tipoComprobante = $cfdiData->getComprobante()->getTipoDeComprobante();
                if ($tipoComprobante !== 'T' && $tipoComprobante !== 'P') {
                    // Para facturas normales, el importe debe ser mayor que 0
                    if (empty($concepto->getImporte()) || (float)$concepto->getImporte() <= 0) {
                        $errors[] = "El importe debe ser mayor a cero en el concepto " . ($index + 1);
                    }
                } else {
                    // Para traslados y pagos, solo verificar que no esté vacío (puede ser 0.00)
                    if ($concepto->getImporte() === null || $concepto->getImporte() === '') {
                        $errors[] = "El importe es requerido en el concepto " . ($index + 1);
                    }
                }
            }
        }

        if (!empty($errors)) {
            return new ValidationResult(false, $errors, [], 'Datos de CFDI inválidos');
        }

        return new ValidationResult(true, [], [], 'Datos de CFDI válidos');
    }
}
