<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Strategies\CartaPorte;

use AlfredoMejia\CfdiPhp\Contracts\ComplementStrategyInterface;
use AlfredoMejia\CfdiPhp\Strategies\CartaPorte\Models\CartaPorteData;
use AlfredoMejia\CfdiPhp\Strategies\CartaPorte\Models\Ubicacion;
use AlfredoMejia\CfdiPhp\Strategies\CartaPorte\Models\Mercancia;
use DOMDocument;
use DOMElement;

/**
 * Estrategia para el complemento de Carta Porte 3.1
 */
class CartaPorteStrategy implements ComplementStrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function generateXML(DOMDocument $xml, DOMElement $complementoElement, object $complementData): void
    {
        if (!$complementData instanceof CartaPorteData) {
            throw new \InvalidArgumentException('Invalid complement data provided for CartaPorteStrategy.');
        }
        
        $cartaPorteElement = $xml->createElement('cartaporte30:CartaPorte');
        $cartaPorteElement->setAttribute('Version', '3.1');
        $cartaPorteElement->setAttribute('TranspInternac', $complementData->getTranspInternac());
        
        // Agregar atributos opcionales
        if ($complementData->getEntradaSalidaMerc()) {
            $cartaPorteElement->setAttribute('EntradaSalidaMerc', $complementData->getEntradaSalidaMerc());
        }
        if ($complementData->getViaEntradaSalida()) {
            $cartaPorteElement->setAttribute('ViaEntradaSalida', $complementData->getViaEntradaSalida());
        }
        if ($complementData->getTotalDistRec()) {
            $cartaPorteElement->setAttribute('TotalDistRec', $complementData->getTotalDistRec());
        }
        if ($complementData->getRegistroISTMO()) {
            $cartaPorteElement->setAttribute('RegistroISTMO', $complementData->getRegistroISTMO());
        }
        if ($complementData->getUbicacionPoloOrigen()) {
            $cartaPorteElement->setAttribute('UbicacionPoloOrigen', $complementData->getUbicacionPoloOrigen());
        }
        if ($complementData->getUbicacionPoloDestino()) {
            $cartaPorteElement->setAttribute('UbicacionPoloDestino', $complementData->getUbicacionPoloDestino());
        }
        if ($complementData->getIdCCP()) {
            $cartaPorteElement->setAttribute('IdCCP', $complementData->getIdCCP());
        }
        if ($complementData->getPaisOrigenDestino()) {
            $cartaPorteElement->setAttribute('PaisOrigenDestino', $complementData->getPaisOrigenDestino());
        }
        
        // Agregar ubicaciones
        $this->addUbicaciones($xml, $cartaPorteElement, $complementData);
        
        // Agregar mercancías
        $this->addMercancias($xml, $cartaPorteElement, $complementData);
        
        // Agregar figura del transporte
        $this->addFiguraTransporte($xml, $cartaPorteElement, $complementData);
        
        $complementoElement->appendChild($cartaPorteElement);
    }
    
    /**
     * Agrega las ubicaciones (origen y destino)
     */
    private function addUbicaciones(DOMDocument $xml, DOMElement $cartaPorteElement, object $complementData): void
    {
        if (!$complementData instanceof CartaPorteData) {
            return;
        }
        
        $ubicacionesElement = $xml->createElement('cartaporte30:Ubicaciones');
        
        // Agregar ubicaciones de origen
        foreach ($complementData->getOrigen() as $origen) {
            if ($origen instanceof Ubicacion) {
                $origenElement = $xml->createElement('cartaporte30:Ubicacion');
                $origenElement->setAttribute('TipoUbicacion', $origen->getTipoUbicacion());
                $origenElement->setAttribute('RFCRemitenteDestinatario', $origen->getRfcRemitenteDestinatario());
                $origenElement->setAttribute('FechaHoraSalidaLlegada', $origen->getFechaHoraSalidaLlegada());
                
                if ($origen->getIdUbicacion()) {
                    $origenElement->setAttribute('IDUbicacion', $origen->getIdUbicacion());
                }
                if ($origen->getNombreRemitenteDestinatario()) {
                    $origenElement->setAttribute('NombreRemitenteDestinatario', $origen->getNombreRemitenteDestinatario());
                }
                if ($origen->getNumRegIdTrib()) {
                    $origenElement->setAttribute('NumRegIdTrib', $origen->getNumRegIdTrib());
                }
                if ($origen->getResidenciaFiscal()) {
                    $origenElement->setAttribute('ResidenciaFiscal', $origen->getResidenciaFiscal());
                }
                if ($origen->getNumEstacion()) {
                    $origenElement->setAttribute('NumEstacion', $origen->getNumEstacion());
                }
                if ($origen->getNombreEstacion()) {
                    $origenElement->setAttribute('NombreEstacion', $origen->getNombreEstacion());
                }
                if ($origen->getNavegacionTrafico()) {
                    $origenElement->setAttribute('NavegacionTrafico', $origen->getNavegacionTrafico());
                }
                if ($origen->getTipoEstacion()) {
                    $origenElement->setAttribute('TipoEstacion', $origen->getTipoEstacion());
                }
                if ($origen->getDistanciaRecorrida()) {
                    $origenElement->setAttribute('DistanciaRecorrida', $origen->getDistanciaRecorrida());
                }
                
                // Agregar domicilio si existe
                if ($origen->getDomicilio()) {
                    $domicilio = $origen->getDomicilio();
                    $domicilioElement = $xml->createElement('cartaporte30:Domicilio');
                    $domicilioElement->setAttribute('Estado', $domicilio->getEstado());
                    $domicilioElement->setAttribute('Pais', $domicilio->getPais());
                    $domicilioElement->setAttribute('CodigoPostal', $domicilio->getCodigoPostal());
                    
                    if ($domicilio->getCalle()) {
                        $domicilioElement->setAttribute('Calle', $domicilio->getCalle());
                    }
                    if ($domicilio->getNumeroExterior()) {
                        $domicilioElement->setAttribute('NumeroExterior', $domicilio->getNumeroExterior());
                    }
                    if ($domicilio->getNumeroInterior()) {
                        $domicilioElement->setAttribute('NumeroInterior', $domicilio->getNumeroInterior());
                    }
                    if ($domicilio->getColonia()) {
                        $domicilioElement->setAttribute('Colonia', $domicilio->getColonia());
                    }
                    if ($domicilio->getLocalidad()) {
                        $domicilioElement->setAttribute('Localidad', $domicilio->getLocalidad());
                    }
                    if ($domicilio->getReferencia()) {
                        $domicilioElement->setAttribute('Referencia', $domicilio->getReferencia());
                    }
                    if ($domicilio->getMunicipio()) {
                        $domicilioElement->setAttribute('Municipio', $domicilio->getMunicipio());
                    }
                    
                    $origenElement->appendChild($domicilioElement);
                }
                
                $ubicacionesElement->appendChild($origenElement);
            }
        }
        
        // Agregar ubicaciones de destino
        foreach ($complementData->getDestino() as $destino) {
            if ($destino instanceof Ubicacion) {
                $destinoElement = $xml->createElement('cartaporte30:Ubicacion');
                $destinoElement->setAttribute('TipoUbicacion', $destino->getTipoUbicacion());
                $destinoElement->setAttribute('RFCRemitenteDestinatario', $destino->getRfcRemitenteDestinatario());
                $destinoElement->setAttribute('FechaHoraSalidaLlegada', $destino->getFechaHoraSalidaLlegada());
                
                if ($destino->getIdUbicacion()) {
                    $destinoElement->setAttribute('IDUbicacion', $destino->getIdUbicacion());
                }
                if ($destino->getNombreRemitenteDestinatario()) {
                    $destinoElement->setAttribute('NombreRemitenteDestinatario', $destino->getNombreRemitenteDestinatario());
                }
                if ($destino->getNumRegIdTrib()) {
                    $destinoElement->setAttribute('NumRegIdTrib', $destino->getNumRegIdTrib());
                }
                if ($destino->getResidenciaFiscal()) {
                    $destinoElement->setAttribute('ResidenciaFiscal', $destino->getResidenciaFiscal());
                }
                if ($destino->getNumEstacion()) {
                    $destinoElement->setAttribute('NumEstacion', $destino->getNumEstacion());
                }
                if ($destino->getNombreEstacion()) {
                    $destinoElement->setAttribute('NombreEstacion', $destino->getNombreEstacion());
                }
                if ($destino->getNavegacionTrafico()) {
                    $destinoElement->setAttribute('NavegacionTrafico', $destino->getNavegacionTrafico());
                }
                if ($destino->getTipoEstacion()) {
                    $destinoElement->setAttribute('TipoEstacion', $destino->getTipoEstacion());
                }
                if ($destino->getDistanciaRecorrida()) {
                    $destinoElement->setAttribute('DistanciaRecorrida', $destino->getDistanciaRecorrida());
                }
                
                // Agregar domicilio si existe
                if ($destino->getDomicilio()) {
                    $domicilio = $destino->getDomicilio();
                    $domicilioElement = $xml->createElement('cartaporte30:Domicilio');
                    $domicilioElement->setAttribute('Estado', $domicilio->getEstado());
                    $domicilioElement->setAttribute('Pais', $domicilio->getPais());
                    $domicilioElement->setAttribute('CodigoPostal', $domicilio->getCodigoPostal());
                    
                    if ($domicilio->getCalle()) {
                        $domicilioElement->setAttribute('Calle', $domicilio->getCalle());
                    }
                    if ($domicilio->getNumeroExterior()) {
                        $domicilioElement->setAttribute('NumeroExterior', $domicilio->getNumeroExterior());
                    }
                    if ($domicilio->getNumeroInterior()) {
                        $domicilioElement->setAttribute('NumeroInterior', $domicilio->getNumeroInterior());
                    }
                    if ($domicilio->getColonia()) {
                        $domicilioElement->setAttribute('Colonia', $domicilio->getColonia());
                    }
                    if ($domicilio->getLocalidad()) {
                        $domicilioElement->setAttribute('Localidad', $domicilio->getLocalidad());
                    }
                    if ($domicilio->getReferencia()) {
                        $domicilioElement->setAttribute('Referencia', $domicilio->getReferencia());
                    }
                    if ($domicilio->getMunicipio()) {
                        $domicilioElement->setAttribute('Municipio', $domicilio->getMunicipio());
                    }
                    
                    $destinoElement->appendChild($domicilioElement);
                }
                
                $ubicacionesElement->appendChild($destinoElement);
            }
        }
        
        $cartaPorteElement->appendChild($ubicacionesElement);
    }
    
    /**
     * Agrega las mercancías
     */
    private function addMercancias(DOMDocument $xml, DOMElement $cartaPorteElement, object $complementData): void
    {
        if (!$complementData instanceof CartaPorteData) {
            return;
        }
        
        $mercanciasElement = $xml->createElement('cartaporte30:Mercancias');
        $mercanciasElement->setAttribute('PesoBrutoTotal', $complementData->getPesoBrutoTotal());
        $mercanciasElement->setAttribute('UnidadPeso', $complementData->getUnidadPeso());
        $mercanciasElement->setAttribute('NumTotalMercancias', $complementData->getNumTotalMercancias());
        
        if ($complementData->getPesoNetoTotal()) {
            $mercanciasElement->setAttribute('PesoNetoTotal', $complementData->getPesoNetoTotal());
        }
        if ($complementData->getCargoPorTasacion()) {
            $mercanciasElement->setAttribute('CargoPorTasacion', $complementData->getCargoPorTasacion());
        }
        if ($complementData->getLogisticaInversaRecoleccionDevolucion()) {
            $mercanciasElement->setAttribute('LogisticaInversaRecoleccionDevolucion', $complementData->getLogisticaInversaRecoleccionDevolucion());
        }
        
        // Agregar mercancías individuales
        foreach ($complementData->getMercancias() as $mercancia) {
            if ($mercancia instanceof Mercancia) {
                $mercanciaElement = $xml->createElement('cartaporte30:Mercancia');
                $mercanciaElement->setAttribute('BienesTransp', $mercancia->getBienesTransp());
                $mercanciaElement->setAttribute('Descripcion', $mercancia->getDescripcion());
                $mercanciaElement->setAttribute('Cantidad', $mercancia->getCantidad());
                $mercanciaElement->setAttribute('ClaveUnidadPeso', $mercancia->getClaveUnidadPeso());
                $mercanciaElement->setAttribute('PesoEnKg', $mercancia->getPesoEnKg());
                
                // Agregar atributos opcionales
                if ($mercancia->getUnidad()) {
                    $mercanciaElement->setAttribute('Unidad', $mercancia->getUnidad());
                }
                if ($mercancia->getDimensiones()) {
                    $mercanciaElement->setAttribute('Dimensiones', $mercancia->getDimensiones());
                }
                if ($mercancia->getMaterialPeligroso()) {
                    $mercanciaElement->setAttribute('MaterialPeligroso', $mercancia->getMaterialPeligroso());
                }
                if ($mercancia->getCveMaterialPeligroso()) {
                    $mercanciaElement->setAttribute('CveMaterialPeligroso', $mercancia->getCveMaterialPeligroso());
                }
                if ($mercancia->getEmbalaje()) {
                    $mercanciaElement->setAttribute('Embalaje', $mercancia->getEmbalaje());
                }
                if ($mercancia->getDescripEmbalaje()) {
                    $mercanciaElement->setAttribute('DescripEmbalaje', $mercancia->getDescripEmbalaje());
                }
                if ($mercancia->getValorMercancia()) {
                    $mercanciaElement->setAttribute('ValorMercancia', $mercancia->getValorMercancia());
                }
                if ($mercancia->getMoneda()) {
                    $mercanciaElement->setAttribute('Moneda', $mercancia->getMoneda());
                }
                if ($mercancia->getFraccionArancelaria()) {
                    $mercanciaElement->setAttribute('FraccionArancelaria', $mercancia->getFraccionArancelaria());
                }
                if ($mercancia->getUuidComercioExt()) {
                    $mercanciaElement->setAttribute('UUIDComercioExt', $mercancia->getUuidComercioExt());
                }
                if ($mercancia->getTipoMateria()) {
                    $mercanciaElement->setAttribute('TipoMateria', $mercancia->getTipoMateria());
                }
                if ($mercancia->getDescripcionMateria()) {
                    $mercanciaElement->setAttribute('DescripcionMateria', $mercancia->getDescripcionMateria());
                }
                if ($mercancia->getNoIdentificacion()) {
                    $mercanciaElement->setAttribute('NoIdentificacion', $mercancia->getNoIdentificacion());
                }
                if ($mercancia->getClaveSTCC()) {
                    $mercanciaElement->setAttribute('ClaveSTCC', $mercancia->getClaveSTCC());
                }
                if ($mercancia->getSectorCOFEPRIS()) {
                    $mercanciaElement->setAttribute('SectorCOFEPRIS', $mercancia->getSectorCOFEPRIS());
                }
                if ($mercancia->getNombreIngredienteActivo()) {
                    $mercanciaElement->setAttribute('NombreIngredienteActivo', $mercancia->getNombreIngredienteActivo());
                }
                if ($mercancia->getNomQuimico()) {
                    $mercanciaElement->setAttribute('NomQuimico', $mercancia->getNomQuimico());
                }
                if ($mercancia->getDenominacionGenericaProd()) {
                    $mercanciaElement->setAttribute('DenominacionGenericaProd', $mercancia->getDenominacionGenericaProd());
                }
                if ($mercancia->getDenominacionDistintivaProd()) {
                    $mercanciaElement->setAttribute('DenominacionDistintivaProd', $mercancia->getDenominacionDistintivaProd());
                }
                if ($mercancia->getFabricante()) {
                    $mercanciaElement->setAttribute('Fabricante', $mercancia->getFabricante());
                }
                if ($mercancia->getFechaCaducidad()) {
                    $mercanciaElement->setAttribute('FechaCaducidad', $mercancia->getFechaCaducidad());
                }
                if ($mercancia->getLoteMedicamento()) {
                    $mercanciaElement->setAttribute('LoteMedicamento', $mercancia->getLoteMedicamento());
                }
                if ($mercancia->getFormaFarmaceutica()) {
                    $mercanciaElement->setAttribute('FormaFarmaceutica', $mercancia->getFormaFarmaceutica());
                }
                if ($mercancia->getCondicionesEspTransp()) {
                    $mercanciaElement->setAttribute('CondicionesEspTransp', $mercancia->getCondicionesEspTransp());
                }
                if ($mercancia->getRegistroSanitarioFolioAutorizacion()) {
                    $mercanciaElement->setAttribute('RegistroSanitarioFolioAutorizacion', $mercancia->getRegistroSanitarioFolioAutorizacion());
                }
                if ($mercancia->getPermisoImportacion()) {
                    $mercanciaElement->setAttribute('PermisoImportacion', $mercancia->getPermisoImportacion());
                }
                if ($mercancia->getFolioImpoVUCEM()) {
                    $mercanciaElement->setAttribute('FolioImpoVUCEM', $mercancia->getFolioImpoVUCEM());
                }
                if ($mercancia->getNumCAS()) {
                    $mercanciaElement->setAttribute('NumCAS', $mercancia->getNumCAS());
                }
                if ($mercancia->getRazonSocialEmpImp()) {
                    $mercanciaElement->setAttribute('RazonSocialEmpImp', $mercancia->getRazonSocialEmpImp());
                }
                if ($mercancia->getNumRegSanPlagCOFEPRIS()) {
                    $mercanciaElement->setAttribute('NumRegSanPlagCOFEPRIS', $mercancia->getNumRegSanPlagCOFEPRIS());
                }
                if ($mercancia->getDatosFabricante()) {
                    $mercanciaElement->setAttribute('DatosFabricante', $mercancia->getDatosFabricante());
                }
                if ($mercancia->getDatosFormulador()) {
                    $mercanciaElement->setAttribute('DatosFormulador', $mercancia->getDatosFormulador());
                }
                if ($mercancia->getDatosMaquilador()) {
                    $mercanciaElement->setAttribute('DatosMaquilador', $mercancia->getDatosMaquilador());
                }
                if ($mercancia->getUsoAutorizado()) {
                    $mercanciaElement->setAttribute('UsoAutorizado', $mercancia->getUsoAutorizado());
                }
                
                // Agregar detalle de mercancía si existe
                if ($mercancia->getDetalleMercancia()) {
                    $detalle = $mercancia->getDetalleMercancia();
                    $detalleElement = $xml->createElement('cartaporte30:DetalleMercancia');
                    $detalleElement->setAttribute('UnidadPesoMerc', $detalle->getUnidadPesoMerc());
                    $detalleElement->setAttribute('PesoBruto', $detalle->getPesoBruto());
                    $detalleElement->setAttribute('PesoNeto', $detalle->getPesoNeto());
                    $detalleElement->setAttribute('PesoTara', $detalle->getPesoTara());
                    
                    if ($detalle->getNumPiezas()) {
                        $detalleElement->setAttribute('NumPiezas', $detalle->getNumPiezas());
                    }
                    
                    $mercanciaElement->appendChild($detalleElement);
                }
                
                $mercanciasElement->appendChild($mercanciaElement);
            }
        }
        
        $cartaPorteElement->appendChild($mercanciasElement);
    }
    
    /**
     * Agrega la figura del transporte
     */
    private function addFiguraTransporte(DOMDocument $xml, DOMElement $cartaPorteElement, object $complementData): void
    {
        if (!$complementData instanceof CartaPorteData) {
            return;
        }
        
        $figuraTransporteElement = $xml->createElement('cartaporte30:FiguraTransporte');
        
        // Agregar operador
        $operador = $complementData->getOperador();
        if ($operador) {
            $operadorElement = $xml->createElement('cartaporte30:Operador');
            $operadorElement->setAttribute('RFCOperador', $operador['rfc']);
            $operadorElement->setAttribute('NombreOperador', $operador['nombre']);
            $figuraTransporteElement->appendChild($operadorElement);
        }
        
        // Agregar propietario
        $propietario = $complementData->getPropietario();
        if ($propietario) {
            $propietarioElement = $xml->createElement('cartaporte30:Propietario');
            $propietarioElement->setAttribute('RFCPropietario', $propietario['rfc']);
            $propietarioElement->setAttribute('NombrePropietario', $propietario['nombre']);
            $figuraTransporteElement->appendChild($propietarioElement);
        }
        
        // Agregar autotransporte
        $autotransporte = $complementData->getAutotransporte();
        if ($autotransporte) {
            $autotransporteElement = $xml->createElement('cartaporte30:AutoTransporte');
            $autotransporteElement->setAttribute('PermSCT', $autotransporte['permSCT']);
            $autotransporteElement->setAttribute('NumPermisoSCT', $autotransporte['numPermisoSCT']);
            
            // Agregar identificación vehicular
            if (isset($autotransporte['identificacionVehicular'])) {
                $identificacionElement = $xml->createElement('cartaporte30:IdentificacionVehicular');
                $identificacionElement->setAttribute('ConfigVehicular', $autotransporte['identificacionVehicular']['configVehicular']);
                $identificacionElement->setAttribute('PlacaVM', $autotransporte['identificacionVehicular']['placaVM']);
                $identificacionElement->setAttribute('AnioModeloVM', $autotransporte['identificacionVehicular']['anioModeloVM']);
                
                $autotransporteElement->appendChild($identificacionElement);
            }
            
            // Agregar seguros
            if (isset($autotransporte['seguros'])) {
                $segurosElement = $xml->createElement('cartaporte30:Seguros');
                $segurosElement->setAttribute('AseguraRespCivil', $autotransporte['seguros']['aseguraRespCivil']);
                $segurosElement->setAttribute('PolizaRespCivil', $autotransporte['seguros']['polizaRespCivil']);
                $segurosElement->setAttribute('AseguraCarga', $autotransporte['seguros']['aseguraCarga']);
                $segurosElement->setAttribute('PolizaCarga', $autotransporte['seguros']['polizaCarga']);
                
                $autotransporteElement->appendChild($segurosElement);
            }
            
            // Agregar remolques
            if (isset($autotransporte['remolques'])) {
                foreach ($autotransporte['remolques'] as $remolque) {
                    $remolqueElement = $xml->createElement('cartaporte30:Remolque');
                    $remolqueElement->setAttribute('SubTipoRem', $remolque['subTipoRem']);
                    $remolqueElement->setAttribute('Placa', $remolque['placa']);
                    
                    $autotransporteElement->appendChild($remolqueElement);
                }
            }
            
            $figuraTransporteElement->appendChild($autotransporteElement);
        }
        
        // Agregar transporte marítimo
        $transporteMaritimo = $complementData->getTransporteMaritimo();
        if ($transporteMaritimo) {
            $transporteMaritimoElement = $xml->createElement('cartaporte30:TransporteMaritimo');
            $transporteMaritimoElement->setAttribute('PermSCT', $transporteMaritimo['permSCT']);
            $transporteMaritimoElement->setAttribute('NumPermisoSCT', $transporteMaritimo['numPermisoSCT']);
            $transporteMaritimoElement->setAttribute('NombreAsegurado', $transporteMaritimo['nombreAsegurado']);
            $transporteMaritimoElement->setAttribute('NumPolizaSeguro', $transporteMaritimo['numPolizaSeguro']);
            
            // Agregar contenedor
            if (isset($transporteMaritimo['contenedor'])) {
                $contenedorElement = $xml->createElement('cartaporte30:Contenedor');
                $contenedorElement->setAttribute('Matricula', $transporteMaritimo['contenedor']['matricula']);
                $contenedorElement->setAttribute('TipoContenedor', $transporteMaritimo['contenedor']['tipoContenedor']);
                
                $transporteMaritimoElement->appendChild($contenedorElement);
            }
            
            $figuraTransporteElement->appendChild($transporteMaritimoElement);
        }
        
        // Agregar transporte aéreo
        $transporteAereo = $complementData->getTransporteAereo();
        if ($transporteAereo) {
            $transporteAereoElement = $xml->createElement('cartaporte30:TransporteAereo');
            $transporteAereoElement->setAttribute('PermSCT', $transporteAereo['permSCT']);
            $transporteAereoElement->setAttribute('NumPermisoSCT', $transporteAereo['numPermisoSCT']);
            $transporteAereoElement->setAttribute('MatriculaAeronave', $transporteAereo['matriculaAeronave']);
            $transporteAereoElement->setAttribute('NombreAsegurado', $transporteAereo['nombreAsegurado']);
            $transporteAereoElement->setAttribute('NumPolizaSeguro', $transporteAereo['numPolizaSeguro']);
            $transporteAereoElement->setAttribute('NumeroGuia', $transporteAereo['numeroGuia']);
            $transporteAereoElement->setAttribute('LugarContrato', $transporteAereo['lugarContrato']);
            $transporteAereoElement->setAttribute('CodigoTransportista', $transporteAereo['codigoTransportista']);
            $transporteAereoElement->setAttribute('RFCEmbarcador', $transporteAereo['rfcEmbarcador']);
            $transporteAereoElement->setAttribute('NumRegIdTribEmbarc', $transporteAereo['numRegIdTribEmbarc']);
            $transporteAereoElement->setAttribute('ResidenciaFiscalEmbarc', $transporteAereo['residenciaFiscalEmbarc']);
            $transporteAereoElement->setAttribute('NombreEmbarcador', $transporteAereo['nombreEmbarcador']);
            
            $figuraTransporteElement->appendChild($transporteAereoElement);
        }
        
        // Agregar transporte ferroviario
        $transporteFerroviario = $complementData->getTransporteFerroviario();
        if ($transporteFerroviario) {
            $transporteFerroviarioElement = $xml->createElement('cartaporte30:TransporteFerroviario');
            $transporteFerroviarioElement->setAttribute('PermSCT', $transporteFerroviario['permSCT']);
            $transporteFerroviarioElement->setAttribute('NumPermisoSCT', $transporteFerroviario['numPermisoSCT']);
            $transporteFerroviarioElement->setAttribute('NombreAsegurado', $transporteFerroviario['nombreAsegurado']);
            $transporteFerroviarioElement->setAttribute('NumPolizaSeguro', $transporteFerroviario['numPolizaSeguro']);
            
            // Agregar derechos de paso
            if (isset($transporteFerroviario['derechosDePaso'])) {
                foreach ($transporteFerroviario['derechosDePaso'] as $derecho) {
                    $derechoElement = $xml->createElement('cartaporte30:DerechosDePaso');
                    $derechoElement->setAttribute('TipoDerechoDePaso', $derecho['tipoDerechoDePaso']);
                    $derechoElement->setAttribute('KilometrajePagado', $derecho['kilometrajePagado']);
                    
                    $transporteFerroviarioElement->appendChild($derechoElement);
                }
            }
            
            // Agregar carro
            if (isset($transporteFerroviario['carro'])) {
                $carroElement = $xml->createElement('cartaporte30:Carro');
                $carroElement->setAttribute('TipoCarro', $transporteFerroviario['carro']['tipoCarro']);
                $carroElement->setAttribute('MatriculaCarro', $transporteFerroviario['carro']['matriculaCarro']);
                $carroElement->setAttribute('GuiasCarro', $transporteFerroviario['carro']['guiasCarro']);
                $carroElement->setAttribute('ToneladasNetasCarro', $transporteFerroviario['carro']['toneladasNetasCarro']);
                
                $transporteFerroviarioElement->appendChild($carroElement);
            }
            
            $figuraTransporteElement->appendChild($transporteFerroviarioElement);
        }
        
        $cartaPorteElement->appendChild($figuraTransporteElement);
    }

    /**
     * {@inheritdoc}
     */
    public function validate(object $complementData): array
    {
        $errors = [];
        
        if (!$complementData instanceof CartaPorteData) {
            $errors[] = 'Los datos del complemento deben ser de tipo CartaPorteData';
            return $errors;
        }
        
        if (empty($complementData->getTranspInternac())) {
            $errors[] = 'El tipo de transporte internacional es requerido';
        }
        
        if (empty($complementData->getOrigen())) {
            $errors[] = 'La ubicación de origen es requerida';
        }
        
        if (empty($complementData->getDestino())) {
            $errors[] = 'La ubicación de destino es requerida';
        }
        
        if (empty($complementData->getMercancias())) {
            $errors[] = 'La información de mercancías es requerida';
        }
        
        if (empty($complementData->getPesoBrutoTotal())) {
            $errors[] = 'El peso bruto total es requerido';
        }
        
        if (empty($complementData->getUnidadPeso())) {
            $errors[] = 'La unidad de peso es requerida';
        }
        
        if (empty($complementData->getNumTotalMercancias())) {
            $errors[] = 'El número total de mercancías es requerido';
        }
        
        return $errors;
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace(): string
    {
        return 'http://www.sat.gob.mx/CartaPorte30';
    }

    /**
     * {@inheritdoc}
     */
    public function getSchemaLocation(): string
    {
        return 'http://www.sat.gob.mx/CartaPorte30 http://www.sat.gob.mx/sitio_internet/cfd/CartaPorte/CartaPorte30.xsd';
    }

    /**
     * {@inheritdoc}
     */
    public function getComplementName(): string
    {
        return 'cartaporte30';
    }
}
