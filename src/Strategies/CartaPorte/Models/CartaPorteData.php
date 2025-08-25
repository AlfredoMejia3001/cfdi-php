<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Strategies\CartaPorte\Models;

/**
 * Contenedor de datos del complemento de Carta Porte
 */
class CartaPorteData
{
    private string $transpInternac;
    private ?string $entradaSalidaMerc = null;
    private ?string $viaEntradaSalida = null;
    private ?string $totalDistRec = null;
    private ?string $registroISTMO = null;
    private ?string $ubicacionPoloOrigen = null;
    private ?string $ubicacionPoloDestino = null;
    private ?string $idCCP = null;
    private ?string $paisOrigenDestino = null;
    private array $origen = [];
    private array $destino = [];
    private array $mercancias = [];
    private string $pesoBrutoTotal;
    private string $unidadPeso;
    private string $numTotalMercancias;
    private ?string $pesoNetoTotal = null;
    private ?string $cargoPorTasacion = null;
    private ?string $logisticaInversaRecoleccionDevolucion = null;
    private array $regimenesAduaneros = [];
    private ?array $operador = null;
    private ?array $propietario = null;
    private ?array $autotransporte = null;
    private ?array $transporteMaritimo = null;
    private ?array $transporteAereo = null;
    private ?array $transporteFerroviario = null;
    
    public function getTranspInternac(): string
    {
        return $this->transpInternac;
    }
    
    public function setTranspInternac(string $transpInternac): self
    {
        $this->transpInternac = $transpInternac;
        return $this;
    }
    
    public function getEntradaSalidaMerc(): ?string
    {
        return $this->entradaSalidaMerc;
    }
    
    public function setEntradaSalidaMerc(?string $entradaSalidaMerc): self
    {
        $this->entradaSalidaMerc = $entradaSalidaMerc;
        return $this;
    }
    
    public function getViaEntradaSalida(): ?string
    {
        return $this->viaEntradaSalida;
    }
    
    public function setViaEntradaSalida(?string $viaEntradaSalida): self
    {
        $this->viaEntradaSalida = $viaEntradaSalida;
        return $this;
    }
    
    public function getTotalDistRec(): ?string
    {
        return $this->totalDistRec;
    }
    
    public function setTotalDistRec(?string $totalDistRec): self
    {
        $this->totalDistRec = $totalDistRec;
        return $this;
    }
    
    public function getRegistroISTMO(): ?string
    {
        return $this->registroISTMO;
    }
    
    public function setRegistroISTMO(?string $registroISTMO): self
    {
        $this->registroISTMO = $registroISTMO;
        return $this;
    }
    
    public function getUbicacionPoloOrigen(): ?string
    {
        return $this->ubicacionPoloOrigen;
    }
    
    public function setUbicacionPoloOrigen(?string $ubicacionPoloOrigen): self
    {
        $this->ubicacionPoloOrigen = $ubicacionPoloOrigen;
        return $this;
    }
    
    public function getUbicacionPoloDestino(): ?string
    {
        return $this->ubicacionPoloDestino;
    }
    
    public function setUbicacionPoloDestino(?string $ubicacionPoloDestino): self
    {
        $this->ubicacionPoloDestino = $ubicacionPoloDestino;
        return $this;
    }
    
    public function getIdCCP(): ?string
    {
        return $this->idCCP;
    }
    
    public function setIdCCP(?string $idCCP): self
    {
        $this->idCCP = $idCCP;
        return $this;
    }
    
    public function getPaisOrigenDestino(): ?string
    {
        return $this->paisOrigenDestino;
    }
    
    public function setPaisOrigenDestino(?string $paisOrigenDestino): self
    {
        $this->paisOrigenDestino = $paisOrigenDestino;
        return $this;
    }
    
    public function getOrigen(): array
    {
        return $this->origen;
    }
    
    public function setOrigen(array $origen): self
    {
        $this->origen = $origen;
        return $this;
    }
    
    public function addOrigen(Ubicacion $origen): self
    {
        $this->origen[] = $origen;
        return $this;
    }
    
    public function getDestino(): array
    {
        return $this->destino;
    }
    
    public function setDestino(array $destino): self
    {
        $this->destino = $destino;
        return $this;
    }
    
    public function addDestino(Ubicacion $destino): self
    {
        $this->destino[] = $destino;
        return $this;
    }
    
    public function getMercancias(): array
    {
        return $this->mercancias;
    }
    
    public function setMercancias(array $mercancias): self
    {
        $this->mercancias = $mercancias;
        return $this;
    }
    
    public function addMercancia(Mercancia $mercancia): self
    {
        $this->mercancias[] = $mercancia;
        return $this;
    }
    
    public function getPesoBrutoTotal(): string
    {
        return $this->pesoBrutoTotal;
    }
    
    public function setPesoBrutoTotal(string $pesoBrutoTotal): self
    {
        $this->pesoBrutoTotal = $pesoBrutoTotal;
        return $this;
    }
    
    public function getUnidadPeso(): string
    {
        return $this->unidadPeso;
    }
    
    public function setUnidadPeso(string $unidadPeso): self
    {
        $this->unidadPeso = $unidadPeso;
        return $this;
    }
    
    public function getNumTotalMercancias(): string
    {
        return $this->numTotalMercancias;
    }
    
    public function setNumTotalMercancias(string $numTotalMercancias): self
    {
        $this->numTotalMercancias = $numTotalMercancias;
        return $this;
    }
    
    public function getPesoNetoTotal(): ?string
    {
        return $this->pesoNetoTotal;
    }
    
    public function setPesoNetoTotal(?string $pesoNetoTotal): self
    {
        $this->pesoNetoTotal = $pesoNetoTotal;
        return $this;
    }
    
    public function getCargoPorTasacion(): ?string
    {
        return $this->cargoPorTasacion;
    }
    
    public function setCargoPorTasacion(?string $cargoPorTasacion): self
    {
        $this->cargoPorTasacion = $cargoPorTasacion;
        return $this;
    }
    
    public function getLogisticaInversaRecoleccionDevolucion(): ?string
    {
        return $this->logisticaInversaRecoleccionDevolucion;
    }
    
    public function setLogisticaInversaRecoleccionDevolucion(?string $logisticaInversaRecoleccionDevolucion): self
    {
        $this->logisticaInversaRecoleccionDevolucion = $logisticaInversaRecoleccionDevolucion;
        return $this;
    }
    
    public function getRegimenesAduaneros(): array
    {
        return $this->regimenesAduaneros;
    }
    
    public function setRegimenesAduaneros(array $regimenesAduaneros): self
    {
        $this->regimenesAduaneros = $regimenesAduaneros;
        return $this;
    }
    
    public function addRegimenAduanero(string $regimenAduanero): self
    {
        $this->regimenesAduaneros[] = $regimenAduanero;
        return $this;
    }
    
    public function getOperador(): ?array
    {
        return $this->operador;
    }
    
    public function setOperador(?array $operador): self
    {
        $this->operador = $operador;
        return $this;
    }
    
    public function getPropietario(): ?array
    {
        return $this->propietario;
    }
    
    public function setPropietario(?array $propietario): self
    {
        $this->propietario = $propietario;
        return $this;
    }
    
    public function getAutotransporte(): ?array
    {
        return $this->autotransporte;
    }
    
    public function setAutotransporte(?array $autotransporte): self
    {
        $this->autotransporte = $autotransporte;
        return $this;
    }
    
    public function getTransporteMaritimo(): ?array
    {
        return $this->transporteMaritimo;
    }
    
    public function setTransporteMaritimo(?array $transporteMaritimo): self
    {
        $this->transporteMaritimo = $transporteMaritimo;
        return $this;
    }
    
    public function getTransporteAereo(): ?array
    {
        return $this->transporteAereo;
    }
    
    public function setTransporteAereo(?array $transporteAereo): self
    {
        $this->transporteAereo = $transporteAereo;
        return $this;
    }
    
    public function getTransporteFerroviario(): ?array
    {
        return $this->transporteFerroviario;
    }
    
    public function setTransporteFerroviario(?array $transporteFerroviario): self
    {
        $this->transporteFerroviario = $transporteFerroviario;
        return $this;
    }
}
