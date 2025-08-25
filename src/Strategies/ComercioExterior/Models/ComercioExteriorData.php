<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Strategies\ComercioExterior\Models;

/**
 * Contenedor de datos del complemento de Comercio Exterior
 */
class ComercioExteriorData
{
    private ?string $motivoTraslado = null;
    private ?string $tipoOperacion = null;
    private ?string $clavePedimento = null;
    private ?string $certificadoOrigen = null;
    private ?string $numCertificadoOrigen = null;
    private ?string $numExportadorConfiable = null;
    private ?string $incoterm = null;
    private ?string $subdivision = null;
    private ?string $observaciones = null;
    private ?string $tipoCambio = null;
    private ?string $totalDolares = null;
    
    public function getMotivoTraslado(): ?string
    {
        return $this->motivoTraslado;
    }
    
    public function setMotivoTraslado(string $motivoTraslado): self
    {
        $this->motivoTraslado = $motivoTraslado;
        return $this;
    }
    
    public function getTipoOperacion(): ?string
    {
        return $this->tipoOperacion;
    }
    
    public function setTipoOperacion(string $tipoOperacion): self
    {
        $this->tipoOperacion = $tipoOperacion;
        return $this;
    }
    
    public function getClavePedimento(): ?string
    {
        return $this->clavePedimento;
    }
    
    public function setClavePedimento(?string $clavePedimento): self
    {
        $this->clavePedimento = $clavePedimento;
        return $this;
    }
    
    public function getCertificadoOrigen(): ?string
    {
        return $this->certificadoOrigen;
    }
    
    public function setCertificadoOrigen(?string $certificadoOrigen): self
    {
        $this->certificadoOrigen = $certificadoOrigen;
        return $this;
    }
    
    public function getNumCertificadoOrigen(): ?string
    {
        return $this->numCertificadoOrigen;
    }
    
    public function setNumCertificadoOrigen(?string $numCertificadoOrigen): self
    {
        $this->numCertificadoOrigen = $numCertificadoOrigen;
        return $this;
    }
    
    public function getNumExportadorConfiable(): ?string
    {
        return $this->numExportadorConfiable;
    }
    
    public function setNumExportadorConfiable(?string $numExportadorConfiable): self
    {
        $this->numExportadorConfiable = $numExportadorConfiable;
        return $this;
    }
    
    public function getIncoterm(): ?string
    {
        return $this->incoterm;
    }
    
    public function setIncoterm(?string $incoterm): self
    {
        $this->incoterm = $incoterm;
        return $this;
    }
    
    public function getSubdivision(): ?string
    {
        return $this->subdivision;
    }
    
    public function setSubdivision(?string $subdivision): self
    {
        $this->subdivision = $subdivision;
        return $this;
    }
    
    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }
    
    public function setObservaciones(?string $observaciones): self
    {
        $this->observaciones = $observaciones;
        return $this;
    }
    
    public function getTipoCambio(): ?string
    {
        return $this->tipoCambio;
    }
    
    public function setTipoCambio(?string $tipoCambio): self
    {
        $this->tipoCambio = $tipoCambio;
        return $this;
    }
    
    public function getTotalDolares(): ?string
    {
        return $this->totalDolares;
    }
    
    public function setTotalDolares(?string $totalDolares): self
    {
        $this->totalDolares = $totalDolares;
        return $this;
    }
}
