<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Strategies\CartaPorte\Models;

/**
 * Modelo para detalle de mercancía en Carta Porte
 */
class DetalleMercancia
{
    private string $unidadPesoMerc;
    private string $pesoBruto;
    private string $pesoNeto;
    private string $pesoTara;
    private ?string $numPiezas = null;
    
    public function getUnidadPesoMerc(): string
    {
        return $this->unidadPesoMerc;
    }
    
    public function setUnidadPesoMerc(string $unidadPesoMerc): self
    {
        $this->unidadPesoMerc = $unidadPesoMerc;
        return $this;
    }
    
    public function getPesoBruto(): string
    {
        return $this->pesoBruto;
    }
    
    public function setPesoBruto(string $pesoBruto): self
    {
        $this->pesoBruto = $pesoBruto;
        return $this;
    }
    
    public function getPesoNeto(): string
    {
        return $this->pesoNeto;
    }
    
    public function setPesoNeto(string $pesoNeto): self
    {
        $this->pesoNeto = $pesoNeto;
        return $this;
    }
    
    public function getPesoTara(): string
    {
        return $this->pesoTara;
    }
    
    public function setPesoTara(string $pesoTara): self
    {
        $this->pesoTara = $pesoTara;
        return $this;
    }
    
    public function getNumPiezas(): ?string
    {
        return $this->numPiezas;
    }
    
    public function setNumPiezas(?string $numPiezas): self
    {
        $this->numPiezas = $numPiezas;
        return $this;
    }
}
