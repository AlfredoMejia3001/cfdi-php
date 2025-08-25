<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Builders;

use AlfredoMejia\CfdiPhp\Models\Impuestos;

/**
 * Builder específico para CFDI 4.0 básico
 */
class CFDI40Builder extends AbstractCFDIBuilder
{
    /**
     * Configura condiciones específicas de pago
     */
    public function condicionesDePago(string $condicionesDePago): self
    {
        $this->cfdiData->getComprobante()->setCondicionesDePago($condicionesDePago);
        return $this;
    }

    /**
     * Configura descuento
     */
    public function descuento(string $descuento): self
    {
        $this->cfdiData->getComprobante()->setDescuento($descuento);
        return $this;
    }

    /**
     * Configura tipo de cambio
     */
    public function tipoCambio(string $tipoCambio): self
    {
        $this->cfdiData->getComprobante()->setTipoCambio($tipoCambio);
        return $this;
    }

    /**
     * Configura confirmación
     */
    public function confirmacion(string $confirmacion): self
    {
        $this->cfdiData->getComprobante()->setConfirmacion($confirmacion);
        return $this;
    }

    /**
     * Configura impuestos totales
     */
    public function impuestosTotales(
        ?string $totalImpuestosRetenidos = null, 
        ?string $totalImpuestosTrasladados = null
    ): self {
        $impuestos = new Impuestos();
        $impuestos
            ->setTotalImpuestosRetenidos($totalImpuestosRetenidos)
            ->setTotalImpuestosTrasladados($totalImpuestosTrasladados);
        
        $this->cfdiData->setImpuestos($impuestos);
        return $this;
    }

    /**
     * Agrega una retención a los impuestos totales
     */
    public function retencionTotal(string $impuesto, string $importe): self
    {
        if (!$this->cfdiData->getImpuestos()) {
            $this->impuestosTotales();
        }
        
        $this->cfdiData->getImpuestos()->addRetencion($impuesto, $importe);
        return $this;
    }

    /**
     * Agrega un traslado a los impuestos totales
     */
    public function trasladoTotal(
        string $base,
        string $impuesto,
        string $tipoFactor,
        string $tasaOCuota,
        string $importe
    ): self {
        if (!$this->cfdiData->getImpuestos()) {
            $this->impuestosTotales();
        }
        
        $this->cfdiData->getImpuestos()->addTraslado($base, $impuesto, $tipoFactor, $tasaOCuota, $importe);
        return $this;
    }

    /**
     * Configura factores de adquiriente
     */
    public function facAtrAdquiriente(string $facAtrAdquiriente): self
    {
        $this->cfdiData->getEmisor()->setFacAtrAdquiriente($facAtrAdquiriente);
        return $this;
    }



    /**
     * Configuración rápida para facturas de ingreso básicas
     */
    public static function factura(): self
    {
        return (new self())
            ->comprobante(
                noCertificado: '30001000000500003416', // Valor por defecto
                tipoDeComprobante: 'I',
                metodoPago: 'PUE',
                formaPago: '01'
            );
    }

    /**
     * Configuración rápida para facturas de egreso (notas de crédito)
     */
    public static function notaCredito(): self
    {
        return (new self())
            ->comprobante(
                noCertificado: '30001000000500003416',
                tipoDeComprobante: 'E',
                metodoPago: 'PUE',
                formaPago: '01'
            );
    }

    /**
     * Configuración rápida para comprobantes de traslado
     */
    public static function createTraslado(): self
    {
        return (new self())
            ->comprobante(
                noCertificado: '30001000000500003416',
                tipoDeComprobante: 'T',
                metodoPago: null,
                formaPago: null,
                total: '0.00'
            );
    }
    
    /**
     * {@inheritdoc}
     */
    protected function configureSpecific(): void
    {
        // Configuraciones específicas para CFDI 4.0 normales
        // Por defecto no hace nada, las configuraciones se realizan mediante los métodos del builder
    }
}
