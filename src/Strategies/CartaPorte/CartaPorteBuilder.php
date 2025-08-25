<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Strategies\CartaPorte;

use AlfredoMejia\CfdiPhp\Builders\AbstractCFDIBuilder;
use AlfredoMejia\CfdiPhp\Strategies\CartaPorte\Models\CartaPorteData;
use AlfredoMejia\CfdiPhp\Strategies\CartaPorte\Models\Ubicacion;
use AlfredoMejia\CfdiPhp\Strategies\CartaPorte\Models\Domicilio;
use AlfredoMejia\CfdiPhp\Strategies\CartaPorte\Models\Mercancia;
use AlfredoMejia\CfdiPhp\Strategies\CartaPorte\Models\DetalleMercancia;

/**
 * Builder específico para CFDI con complemento de Carta Porte
 */
class CartaPorteBuilder extends AbstractCFDIBuilder
{
    private CartaPorteData $cartaPorteData;
    
    public function __construct($xmlGenerator = null, $validationService = null)
    {
        parent::__construct($xmlGenerator, $validationService);
        $this->cartaPorteData = new CartaPorteData();
    }
    
    /**
     * Configurar transporte internacional
     */
    public function transporteInternacional(string $tipo): self
    {
        $this->cartaPorteData->setTranspInternac($tipo);
        return $this;
    }
    
    /**
     * Configurar ubicación de origen
     */
    public function origen(
        string $idUbicacion,
        string $rfcRemitente,
        string $fechaHora,
        array $domicilio
    ): self {
        $ubicacion = new Ubicacion();
        $ubicacion->setIdUbicacion($idUbicacion);
        $ubicacion->setTipoUbicacion('Origen');
        $ubicacion->setRfcRemitenteDestinatario($rfcRemitente);
        $ubicacion->setFechaHoraSalidaLlegada($fechaHora);
        
        // Crear y configurar el domicilio
        $domicilioObj = new Domicilio();
        $domicilioObj->setEstado($domicilio['estado']);
        $domicilioObj->setPais($domicilio['pais']);
        $domicilioObj->setCodigoPostal($domicilio['codigoPostal']);
        
        if (isset($domicilio['calle'])) {
            $domicilioObj->setCalle($domicilio['calle']);
        }
        if (isset($domicilio['numeroExterior'])) {
            $domicilioObj->setNumeroExterior($domicilio['numeroExterior']);
        }
        if (isset($domicilio['numeroInterior'])) {
            $domicilioObj->setNumeroInterior($domicilio['numeroInterior']);
        }
        if (isset($domicilio['colonia'])) {
            $domicilioObj->setColonia($domicilio['colonia']);
        }
        if (isset($domicilio['localidad'])) {
            $domicilioObj->setLocalidad($domicilio['localidad']);
        }
        if (isset($domicilio['referencia'])) {
            $domicilioObj->setReferencia($domicilio['referencia']);
        }
        if (isset($domicilio['municipio'])) {
            $domicilioObj->setMunicipio($domicilio['municipio']);
        }
        
        $ubicacion->setDomicilio($domicilioObj);
        
        $this->cartaPorteData->addOrigen($ubicacion);
        return $this;
    }
    
    /**
     * Configurar ubicación de destino
     */
    public function destino(
        string $idUbicacion,
        string $rfcDestinatario,
        string $fechaHora,
        array $domicilio
    ): self {
        $ubicacion = new Ubicacion();
        $ubicacion->setIdUbicacion($idUbicacion);
        $ubicacion->setTipoUbicacion('Destino');
        $ubicacion->setRfcRemitenteDestinatario($rfcDestinatario);
        $ubicacion->setFechaHoraSalidaLlegada($fechaHora);
        
        // Crear y configurar el domicilio
        $domicilioObj = new Domicilio();
        $domicilioObj->setEstado($domicilio['estado']);
        $domicilioObj->setPais($domicilio['pais']);
        $domicilioObj->setCodigoPostal($domicilio['codigoPostal']);
        
        if (isset($domicilio['calle'])) {
            $domicilioObj->setCalle($domicilio['calle']);
        }
        if (isset($domicilio['numeroExterior'])) {
            $domicilioObj->setNumeroExterior($domicilio['numeroExterior']);
        }
        if (isset($domicilio['numeroInterior'])) {
            $domicilioObj->setNumeroInterior($domicilio['numeroInterior']);
        }
        if (isset($domicilio['colonia'])) {
            $domicilioObj->setColonia($domicilio['colonia']);
        }
        if (isset($domicilio['localidad'])) {
            $domicilioObj->setLocalidad($domicilio['localidad']);
        }
        if (isset($domicilio['referencia'])) {
            $domicilioObj->setReferencia($domicilio['referencia']);
        }
        if (isset($domicilio['municipio'])) {
            $domicilioObj->setMunicipio($domicilio['municipio']);
        }
        
        $ubicacion->setDomicilio($domicilioObj);
        
        $this->cartaPorteData->addDestino($ubicacion);
        return $this;
    }
    
    /**
     * Configurar mercancía
     */
    public function mercancia(
        string $bienesTransp,
        string $descripcion,
        string $cantidad,
        string $claveUnidadPeso,
        string $pesoEnKg
    ): self {
        $mercancia = new Mercancia();
        $mercancia->setBienesTransp($bienesTransp);
        $mercancia->setDescripcion($descripcion);
        $mercancia->setCantidad($cantidad);
        $mercancia->setClaveUnidad($claveUnidadPeso);
        $mercancia->setPesoEnKg($pesoEnKg);
        
        $this->cartaPorteData->addMercancia($mercancia);
        return $this;
    }
    
    /**
     * Configurar totales de mercancías
     */
    public function totalesMercancias(
        string $pesoBrutoTotal,
        string $unidadPeso,
        string $numTotalMercancias
    ): self {
        $this->cartaPorteData->setPesoBrutoTotal($pesoBrutoTotal);
        $this->cartaPorteData->setUnidadPeso($unidadPeso);
        $this->cartaPorteData->setNumTotalMercancias($numTotalMercancias);
        return $this;
    }
    
    /**
     * Configurar operador del transporte
     */
    public function operador(string $rfc, string $nombre): self
    {
        $this->cartaPorteData->setOperador([
            'rfc' => $rfc,
            'nombre' => $nombre
        ]);
        return $this;
    }
    
    /**
     * Configurar propietario del transporte
     */
    public function propietario(string $rfc, string $nombre): self
    {
        $this->cartaPorteData->setPropietario([
            'rfc' => $rfc,
            'nombre' => $nombre
        ]);
        return $this;
    }
    
    /**
     * Configuración específica para CFDI con Carta Porte
     */
    protected function configureSpecific(): void
    {
        // Los CFDI con Carta Porte mantienen su tipo original
        // pero se agrega el complemento
        
        // Agregar los datos del complemento de Carta Porte
        $this->cfdiData->setComplemento($this->cartaPorteData);
    }
    
    /**
     * Obtener los datos del complemento de Carta Porte
     */
    public function getCartaPorteData(): CartaPorteData
    {
        return $this->cartaPorteData;
    }
    
    /**
     * Método estático para crear rápidamente un CFDI con Carta Porte
     */
    public static function createCartaPorte(
        string $transpInternac,
        array $origen,
        array $destino,
        array $mercancia
    ): self {
        return (new self())
            ->transporteInternacional($transpInternac)
            ->origen(
                $origen['idUbicacion'],
                $origen['rfcRemitente'],
                $origen['fechaHora'],
                $origen['domicilio']
            )
            ->destino(
                $destino['idUbicacion'],
                $destino['rfcDestinatario'],
                $destino['fechaHora'],
                $destino['domicilio']
            )
            ->mercancia(
                $mercancia['bienesTransp'],
                $mercancia['descripcion'],
                $mercancia['cantidad'],
                $mercancia['claveUnidadPeso'],
                $mercancia['pesoEnKg']
            )
            ->totalesMercancias(
                $mercancia['pesoEnKg'],
                'KGM',
                '1'
            );
    }
}
