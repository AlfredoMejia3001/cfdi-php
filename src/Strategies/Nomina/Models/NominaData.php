<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Strategies\Nomina\Models;

/**
 * Contenedor de datos del complemento de Nómina
 */
class NominaData
{
    private string $tipoNomina;
    private string $fechaPago;
    private string $fechaInicialPago;
    private string $fechaFinalPago;
    private string $numDiasPagados;
    private ?string $totalPercepciones = null;
    private ?string $totalDeducciones = null;
    private ?string $totalOtrosPagos = null;
    private ?array $emisor = null;
    private ?array $receptor = null;
    private ?array $percepciones = null;
    private ?array $deducciones = null;
    private ?array $otrosPagos = null;
    private ?array $incapacidades = null;
    
    public function getTipoNomina(): string
    {
        return $this->tipoNomina;
    }
    
    public function setTipoNomina(string $tipoNomina): self
    {
        $this->tipoNomina = $tipoNomina;
        return $this;
    }
    
    public function getFechaPago(): string
    {
        return $this->fechaPago;
    }
    
    public function setFechaPago(string $fechaPago): self
    {
        $this->fechaPago = $fechaPago;
        return $this;
    }
    
    public function getFechaInicialPago(): string
    {
        return $this->fechaInicialPago;
    }
    
    public function setFechaInicialPago(string $fechaInicialPago): self
    {
        $this->fechaInicialPago = $fechaInicialPago;
        return $this;
    }
    
    public function getFechaFinalPago(): string
    {
        return $this->fechaFinalPago;
    }
    
    public function setFechaFinalPago(string $fechaFinalPago): self
    {
        $this->fechaFinalPago = $fechaFinalPago;
        return $this;
    }
    
    public function getNumDiasPagados(): string
    {
        return $this->numDiasPagados;
    }
    
    public function setNumDiasPagados(string $numDiasPagados): self
    {
        $this->numDiasPagados = $numDiasPagados;
        return $this;
    }
    
    public function getTotalPercepciones(): ?string
    {
        return $this->totalPercepciones;
    }
    
    public function setTotalPercepciones(?string $totalPercepciones): self
    {
        $this->totalPercepciones = $totalPercepciones;
        return $this;
    }
    
    public function getTotalDeducciones(): ?string
    {
        return $this->totalDeducciones;
    }
    
    public function setTotalDeducciones(?string $totalDeducciones): self
    {
        $this->totalDeducciones = $totalDeducciones;
        return $this;
    }
    
    public function getTotalOtrosPagos(): ?string
    {
        return $this->totalOtrosPagos;
    }
    
    public function setTotalOtrosPagos(?string $totalOtrosPagos): self
    {
        $this->totalOtrosPagos = $totalOtrosPagos;
        return $this;
    }
    
    public function getEmisor(): ?array
    {
        return $this->emisor;
    }
    
    public function setEmisor(?array $emisor): self
    {
        $this->emisor = $emisor;
        return $this;
    }
    
    public function getReceptor(): ?array
    {
        return $this->receptor;
    }
    
    public function setReceptor(?array $receptor): self
    {
        $this->receptor = $receptor;
        return $this;
    }
    
    public function getPercepciones(): ?array
    {
        return $this->percepciones;
    }
    
    public function setPercepciones(?array $percepciones): self
    {
        $this->percepciones = $percepciones;
        return $this;
    }
    
    public function getDeducciones(): ?array
    {
        return $this->deducciones;
    }
    
    public function setDeducciones(?array $deducciones): self
    {
        $this->deducciones = $deducciones;
        return $this;
    }
    
    public function getOtrosPagos(): ?array
    {
        return $this->otrosPagos;
    }
    
    public function setOtrosPagos(?array $otrosPagos): self
    {
        $this->otrosPagos = $otrosPagos;
        return $this;
    }
    
    public function getIncapacidades(): ?array
    {
        return $this->incapacidades;
    }
    
    public function setIncapacidades(?array $incapacidades): self
    {
        $this->incapacidades = $incapacidades;
        return $this;
    }
}
