<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Strategies\Pagos\Models;

/**
 * Modelo para documentos relacionados en el complemento de Pagos
 */
class DoctoRelacionado
{
    private string $idDocumento;
    private string $monedaDR;
    private string $metodoDePagoDR;
    private string $numParcialidad;
    private string $impSaldoAnt;
    private string $impPagado;
    private string $impSaldoInsoluto;
    private ?string $tipoCambioDR = null;
    private array $trasladosDR = [];
    private array $retencionesDR = [];
    private ?array $totalesDR = null;
    
    public function getIdDocumento(): string
    {
        return $this->idDocumento;
    }
    
    public function setIdDocumento(string $idDocumento): self
    {
        $this->idDocumento = $idDocumento;
        return $this;
    }
    
    public function getMonedaDR(): string
    {
        return $this->monedaDR;
    }
    
    public function setMonedaDR(string $monedaDR): self
    {
        $this->monedaDR = $monedaDR;
        return $this;
    }
    
    public function getMetodoDePagoDR(): string
    {
        return $this->metodoDePagoDR;
    }
    
    public function setMetodoDePagoDR(string $metodoDePagoDR): self
    {
        $this->metodoDePagoDR = $metodoDePagoDR;
        return $this;
    }
    
    public function getNumParcialidad(): string
    {
        return $this->numParcialidad;
    }
    
    public function setNumParcialidad(string $numParcialidad): self
    {
        $this->numParcialidad = $numParcialidad;
        return $this;
    }
    
    public function getImpSaldoAnt(): string
    {
        return $this->impSaldoAnt;
    }
    
    public function setImpSaldoAnt(string $impSaldoAnt): self
    {
        $this->impSaldoAnt = $impSaldoAnt;
        return $this;
    }
    
    public function getImpPagado(): string
    {
        return $this->impPagado;
    }
    
    public function setImpPagado(string $impPagado): self
    {
        $this->impPagado = $impPagado;
        return $this;
    }
    
    public function getImpSaldoInsoluto(): string
    {
        return $this->impSaldoInsoluto;
    }
    
    public function setImpSaldoInsoluto(string $impSaldoInsoluto): self
    {
        $this->impSaldoInsoluto = $impSaldoInsoluto;
        return $this;
    }
    
    public function getTipoCambioDR(): ?string
    {
        return $this->tipoCambioDR;
    }
    
    public function setTipoCambioDR(?string $tipoCambioDR): self
    {
        $this->tipoCambioDR = $tipoCambioDR;
        return $this;
    }
    
    public function getTrasladosDR(): array
    {
        return $this->trasladosDR;
    }
    
    public function addTrasladoDR(array $traslado): self
    {
        $this->trasladosDR[] = $traslado;
        return $this;
    }
    
    public function setTrasladosDR(array $traslados): self
    {
        $this->trasladosDR = $traslados;
        return $this;
    }
    
    public function getRetencionesDR(): array
    {
        return $this->retencionesDR;
    }
    
    public function addRetencionDR(array $retencion): self
    {
        $this->retencionesDR[] = $retencion;
        return $this;
    }
    
    public function setRetencionesDR(array $retenciones): self
    {
        $this->retencionesDR = $retenciones;
        return $this;
    }
    
    public function getTotalesDR(): ?array
    {
        return $this->totalesDR;
    }
    
    public function setTotalesDR(?array $totales): self
    {
        $this->totalesDR = $totales;
        return $this;
    }
}
