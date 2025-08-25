<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Models;

/**
 * Modelo para los conceptos del CFDI
 */
class Concepto
{
    private string $claveProdServ;
    private string $cantidad;
    private string $claveUnidad;
    private string $descripcion;
    private string $valorUnitario;
    private string $importe;
    private string $objetoImp;
    private ?string $noIdentificacion = null;
    private ?string $unidad = null;
    private ?string $descuento = null;
    private array $traslados = [];
    private array $retenciones = [];

    public function getClaveProdServ(): string
    {
        return $this->claveProdServ;
    }

    public function setClaveProdServ(string $claveProdServ): self
    {
        $this->claveProdServ = $claveProdServ;
        return $this;
    }

    public function getCantidad(): string
    {
        return $this->cantidad;
    }

    public function setCantidad(string $cantidad): self
    {
        $this->cantidad = $cantidad;
        return $this;
    }

    public function getClaveUnidad(): string
    {
        return $this->claveUnidad;
    }

    public function setClaveUnidad(string $claveUnidad): self
    {
        $this->claveUnidad = $claveUnidad;
        return $this;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;
        return $this;
    }

    public function getValorUnitario(): string
    {
        return $this->valorUnitario;
    }

    public function setValorUnitario(string $valorUnitario): self
    {
        $this->valorUnitario = $valorUnitario;
        return $this;
    }

    public function getImporte(): string
    {
        return $this->importe;
    }

    public function setImporte(string $importe): self
    {
        $this->importe = $importe;
        return $this;
    }

    public function getObjetoImp(): string
    {
        return $this->objetoImp;
    }

    public function setObjetoImp(string $objetoImp): self
    {
        $this->objetoImp = $objetoImp;
        return $this;
    }

    public function getNoIdentificacion(): ?string
    {
        return $this->noIdentificacion;
    }

    public function setNoIdentificacion(?string $noIdentificacion): self
    {
        $this->noIdentificacion = $noIdentificacion;
        return $this;
    }

    public function getUnidad(): ?string
    {
        return $this->unidad;
    }

    public function setUnidad(?string $unidad): self
    {
        $this->unidad = $unidad;
        return $this;
    }

    public function getDescuento(): ?string
    {
        return $this->descuento;
    }

    public function setDescuento(?string $descuento): self
    {
        $this->descuento = $descuento;
        return $this;
    }

    public function getTraslados(): array
    {
        return $this->traslados;
    }

    public function addTraslado(array $traslado): self
    {
        $this->traslados[] = $traslado;
        return $this;
    }

    public function getRetenciones(): array
    {
        return $this->retenciones;
    }

    public function addRetencion(array $retencion): self
    {
        $this->retenciones[] = $retencion;
        return $this;
    }
}
