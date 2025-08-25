<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Models;

/**
 * Modelo para los datos del comprobante
 */
class Comprobante
{
    private string $version = '4.0';
    private ?string $noCertificado = null;
    private ?string $serie = null;
    private ?string $folio = null;
    private ?string $fecha = null;
    private ?string $subTotal = null;
    private ?string $moneda = null;
    private ?string $total = null;
    private ?string $tipoDeComprobante = null;
    private ?string $exportacion = null;
    private ?string $metodoPago = null;
    private ?string $formaPago = null;
    private ?string $lugarExpedicion = null;
    private ?string $condicionesDePago = null;
    private ?string $descuento = null;
    private ?string $tipoCambio = null;
    private ?string $confirmacion = null;

    public function __construct()
    {
        // La fecha se asignará cuando se configure el comprobante
    }

    // Getters
    public function getVersion(): string
    {
        return $this->version;
    }

    public function getNoCertificado(): ?string
    {
        return $this->noCertificado;
    }

    public function getSerie(): ?string
    {
        return $this->serie;
    }

    public function getFolio(): ?string
    {
        return $this->folio;
    }

    public function getFecha(): ?string
    {
        return $this->fecha;
    }

    public function getSubTotal(): ?string
    {
        return $this->subTotal;
    }

    public function getMoneda(): ?string
    {
        return $this->moneda;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function getTipoDeComprobante(): ?string
    {
        return $this->tipoDeComprobante;
    }

    public function getExportacion(): ?string
    {
        return $this->exportacion;
    }

    public function getMetodoPago(): ?string
    {
        return $this->metodoPago;
    }

    public function getFormaPago(): ?string
    {
        return $this->formaPago;
    }

    public function getLugarExpedicion(): ?string
    {
        return $this->lugarExpedicion;
    }

    public function getCondicionesDePago(): ?string
    {
        return $this->condicionesDePago;
    }

    public function getDescuento(): ?string
    {
        return $this->descuento;
    }

    public function getTipoCambio(): ?string
    {
        return $this->tipoCambio;
    }

    public function getConfirmacion(): ?string
    {
        return $this->confirmacion;
    }

    // Setters
    public function setNoCertificado(string $noCertificado): self
    {
        $this->noCertificado = $noCertificado;
        return $this;
    }

    public function setSerie(?string $serie): self
    {
        $this->serie = $serie;
        return $this;
    }

    public function setFolio(?string $folio): self
    {
        $this->folio = $folio;
        return $this;
    }

    public function setFecha(?string $fecha): self
    {
        $this->fecha = $fecha ?: date('Y-m-d\TH:i:s');
        return $this;
    }

    public function setSubTotal(string $subTotal): self
    {
        $this->subTotal = $subTotal;
        return $this;
    }

    public function setMoneda(string $moneda): self
    {
        $this->moneda = $moneda;
        return $this;
    }

    public function setTotal(string $total): self
    {
        $this->total = $total;
        return $this;
    }

    public function setTipoDeComprobante(string $tipoDeComprobante): self
    {
        $this->tipoDeComprobante = $tipoDeComprobante;
        return $this;
    }

    public function setExportacion(string $exportacion): self
    {
        $this->exportacion = $exportacion;
        return $this;
    }

    public function setMetodoPago(?string $metodoPago): self
    {
        $this->metodoPago = $metodoPago;
        return $this;
    }

    public function setFormaPago(?string $formaPago): self
    {
        $this->formaPago = $formaPago;
        return $this;
    }

    public function setLugarExpedicion(string $lugarExpedicion): self
    {
        $this->lugarExpedicion = $lugarExpedicion;
        return $this;
    }

    public function setCondicionesDePago(?string $condicionesDePago): self
    {
        $this->condicionesDePago = $condicionesDePago;
        return $this;
    }

    public function setDescuento(?string $descuento): self
    {
        $this->descuento = $descuento;
        return $this;
    }

    public function setTipoCambio(?string $tipoCambio): self
    {
        $this->tipoCambio = $tipoCambio;
        return $this;
    }

    public function setConfirmacion(?string $confirmacion): self
    {
        $this->confirmacion = $confirmacion;
        return $this;
    }
}
