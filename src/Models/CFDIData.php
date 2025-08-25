<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Models;

/**
 * Modelo que encapsula todos los datos de un CFDI
 */
class CFDIData
{
    private Comprobante $comprobante;
    private Emisor $emisor;
    private Receptor $receptor;
    private array $conceptos = [];
    private ?Impuestos $impuestos = null;
    private ?object $complemento = null;
    private array $cfdiRelacionados = [];

    public function __construct()
    {
        $this->comprobante = new Comprobante();
        $this->emisor = new Emisor();
        $this->receptor = new Receptor();
    }

    public function getComprobante(): Comprobante
    {
        return $this->comprobante;
    }

    public function setComprobante(Comprobante $comprobante): self
    {
        $this->comprobante = $comprobante;
        return $this;
    }

    public function getEmisor(): Emisor
    {
        return $this->emisor;
    }

    public function setEmisor(Emisor $emisor): self
    {
        $this->emisor = $emisor;
        return $this;
    }

    public function getReceptor(): Receptor
    {
        return $this->receptor;
    }

    public function setReceptor(Receptor $receptor): self
    {
        $this->receptor = $receptor;
        return $this;
    }

    public function getConceptos(): array
    {
        return $this->conceptos;
    }

    public function addConcepto(Concepto $concepto): self
    {
        $this->conceptos[] = $concepto;
        return $this;
    }

    public function setConceptos(array $conceptos): self
    {
        $this->conceptos = $conceptos;
        return $this;
    }

    public function getImpuestos(): ?Impuestos
    {
        return $this->impuestos;
    }

    public function setImpuestos(?Impuestos $impuestos): self
    {
        $this->impuestos = $impuestos;
        return $this;
    }

    public function getComplemento(): ?object
    {
        return $this->complemento;
    }

    public function setComplemento(?object $complemento): self
    {
        $this->complemento = $complemento;
        return $this;
    }

    public function getCfdiRelacionados(): array
    {
        return $this->cfdiRelacionados;
    }

    public function addCfdiRelacionado(string $uuid, string $tipoRelacion = '04'): self
    {
        $this->cfdiRelacionados[] = [
            'tipoRelacion' => $tipoRelacion,
            'uuid' => $uuid
        ];
        return $this;
    }
}
