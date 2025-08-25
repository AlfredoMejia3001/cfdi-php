<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Strategies\CartaPorte\Models;

/**
 * Modelo para ubicaciones en Carta Porte
 */
class Ubicacion
{
    private string $tipoUbicacion;
    private string $rfcRemitenteDestinatario;
    private string $fechaHoraSalidaLlegada;
    private ?string $idUbicacion = null;
    private ?string $nombreRemitenteDestinatario = null;
    private ?string $numRegIdTrib = null;
    private ?string $residenciaFiscal = null;
    private ?string $numEstacion = null;
    private ?string $nombreEstacion = null;
    private ?string $navegacionTrafico = null;
    private ?string $tipoEstacion = null;
    private ?string $distanciaRecorrida = null;
    private ?Domicilio $domicilio = null;
    
    public function getTipoUbicacion(): string
    {
        return $this->tipoUbicacion;
    }
    
    public function setTipoUbicacion(string $tipoUbicacion): self
    {
        $this->tipoUbicacion = $tipoUbicacion;
        return $this;
    }
    
    public function getRfcRemitenteDestinatario(): string
    {
        return $this->rfcRemitenteDestinatario;
    }
    
    public function setRfcRemitenteDestinatario(string $rfcRemitenteDestinatario): self
    {
        $this->rfcRemitenteDestinatario = $rfcRemitenteDestinatario;
        return $this;
    }
    
    public function getFechaHoraSalidaLlegada(): string
    {
        return $this->fechaHoraSalidaLlegada;
    }
    
    public function setFechaHoraSalidaLlegada(string $fechaHoraSalidaLlegada): self
    {
        $this->fechaHoraSalidaLlegada = $fechaHoraSalidaLlegada;
        return $this;
    }
    
    public function getIdUbicacion(): ?string
    {
        return $this->idUbicacion;
    }
    
    public function setIdUbicacion(?string $idUbicacion): self
    {
        $this->idUbicacion = $idUbicacion;
        return $this;
    }
    
    public function getNombreRemitenteDestinatario(): ?string
    {
        return $this->nombreRemitenteDestinatario;
    }
    
    public function setNombreRemitenteDestinatario(?string $nombreRemitenteDestinatario): self
    {
        $this->nombreRemitenteDestinatario = $nombreRemitenteDestinatario;
        return $this;
    }
    
    public function getNumRegIdTrib(): ?string
    {
        return $this->numRegIdTrib;
    }
    
    public function setNumRegIdTrib(?string $numRegIdTrib): self
    {
        $this->numRegIdTrib = $numRegIdTrib;
        return $this;
    }
    
    public function getResidenciaFiscal(): ?string
    {
        return $this->residenciaFiscal;
    }
    
    public function setResidenciaFiscal(?string $residenciaFiscal): self
    {
        $this->residenciaFiscal = $residenciaFiscal;
        return $this;
    }
    
    public function getNumEstacion(): ?string
    {
        return $this->numEstacion;
    }
    
    public function setNumEstacion(?string $numEstacion): self
    {
        $this->numEstacion = $numEstacion;
        return $this;
    }
    
    public function getNombreEstacion(): ?string
    {
        return $this->nombreEstacion;
    }
    
    public function setNombreEstacion(?string $nombreEstacion): self
    {
        $this->nombreEstacion = $nombreEstacion;
        return $this;
    }
    
    public function getNavegacionTrafico(): ?string
    {
        return $this->navegacionTrafico;
    }
    
    public function setNavegacionTrafico(?string $navegacionTrafico): self
    {
        $this->navegacionTrafico = $navegacionTrafico;
        return $this;
    }
    
    public function getTipoEstacion(): ?string
    {
        return $this->tipoEstacion;
    }
    
    public function setTipoEstacion(?string $tipoEstacion): self
    {
        $this->tipoEstacion = $tipoEstacion;
        return $this;
    }
    
    public function getDistanciaRecorrida(): ?string
    {
        return $this->distanciaRecorrida;
    }
    
    public function setDistanciaRecorrida(?string $distanciaRecorrida): self
    {
        $this->distanciaRecorrida = $distanciaRecorrida;
        return $this;
    }
    
    public function getDomicilio(): ?Domicilio
    {
        return $this->domicilio;
    }
    
    public function setDomicilio(?Domicilio $domicilio): self
    {
        $this->domicilio = $domicilio;
        return $this;
    }
}
