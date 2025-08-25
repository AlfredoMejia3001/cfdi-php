<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Contracts;

use AlfredoMejia\CfdiPhp\Models\CFDIData;

/**
 * Interface para constructores de CFDI
 * Define el contrato que deben cumplir todos los builders de CFDI
 */
interface CFDIBuilderInterface
{
    /**
     * Configura los datos principales del comprobante
     */
    public function comprobante(
        string $noCertificado,
        ?string $serie = null,
        ?string $folio = null,
        ?string $fecha = null,
        string $subTotal = '0.00',
        string $moneda = 'MXN',
        string $total = '0.00',
        string $tipoDeComprobante = 'I',
        string $exportacion = '01',
        ?string $metodoPago = null,
        ?string $formaPago = null,
        string $lugarExpedicion = '06500'
    ): self;

    /**
     * Configura los datos del emisor
     */
    public function emisor(
        string $rfc,
        string $nombre,
        string $regimenFiscal,
        ?string $facAtrAdquiriente = null
    ): self;

    /**
     * Configura los datos del receptor
     */
    public function receptor(
        string $rfc,
        string $nombre,
        string $regimenFiscalReceptor,
        string $usoCFDI,
        string $domicilioFiscalReceptor,
        ?string $residenciaFiscal = null,
        ?string $numRegIdTrib = null
    ): self;

    /**
     * Agrega un concepto al CFDI
     */
    public function concepto(
        string $claveProdServ,
        string $cantidad,
        string $claveUnidad,
        string $descripcion,
        string $valorUnitario,
        string $importe,
        string $objetoImp,
        ?string $noIdentificacion = null,
        ?string $unidad = null,
        ?string $descuento = null
    ): self;

    /**
     * Construye y retorna los datos del CFDI
     */
    public function build(): CFDIData;

    /**
     * Resetea el builder para construir un nuevo CFDI
     */
    public function reset(): self;
}
