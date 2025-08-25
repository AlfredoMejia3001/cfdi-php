<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Strategies\CartaPorte\Models;

/**
 * Modelo para domicilios en Carta Porte
 */
class Domicilio
{
    private string $estado;
    private string $pais;
    private string $codigoPostal;
    private ?string $calle = null;
    private ?string $numeroExterior = null;
    private ?string $numeroInterior = null;
    private ?string $colonia = null;
    private ?string $localidad = null;
    private ?string $referencia = null;
    private ?string $municipio = null;
    
    public function getEstado(): string
    {
        return $this->estado;
    }
    
    public function setEstado(string $estado): self
    {
        $this->estado = $estado;
        return $this;
    }
    
    public function getPais(): string
    {
        return $this->pais;
    }
    
    public function setPais(string $pais): self
    {
        $this->pais = $pais;
        return $this;
    }
    
    public function getCodigoPostal(): string
    {
        return $this->codigoPostal;
    }
    
    public function setCodigoPostal(string $codigoPostal): self
    {
        $this->codigoPostal = $codigoPostal;
        return $this;
    }
    
    public function getCalle(): ?string
    {
        return $this->calle;
    }
    
    public function setCalle(?string $calle): self
    {
        $this->calle = $calle;
        return $this;
    }
    
    public function getNumeroExterior(): ?string
    {
        return $this->numeroExterior;
    }
    
    public function setNumeroExterior(?string $numeroExterior): self
    {
        $this->numeroExterior = $numeroExterior;
        return $this;
    }
    
    public function getNumeroInterior(): ?string
    {
        return $this->numeroInterior;
    }
    
    public function setNumeroInterior(?string $numeroInterior): self
    {
        $this->numeroInterior = $numeroInterior;
        return $this;
    }
    
    public function getColonia(): ?string
    {
        return $this->colonia;
    }
    
    public function setColonia(?string $colonia): self
    {
        $this->colonia = $colonia;
        return $this;
    }
    
    public function getLocalidad(): ?string
    {
        return $this->localidad;
    }
    
    public function setLocalidad(?string $localidad): self
    {
        $this->localidad = $localidad;
        return $this;
    }
    
    public function getReferencia(): ?string
    {
        return $this->referencia;
    }
    
    public function setReferencia(?string $referencia): self
    {
        $this->referencia = $referencia;
        return $this;
    }
    
    public function getMunicipio(): ?string
    {
        return $this->municipio;
    }
    
    public function setMunicipio(?string $municipio): self
    {
        $this->municipio = $municipio;
        return $this;
    }
}
