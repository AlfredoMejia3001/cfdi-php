<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Strategies\ComercioExterior;

use AlfredoMejia\CfdiPhp\Contracts\ComplementStrategyInterface;
use DOMDocument;
use DOMElement;

/**
 * Estrategia para el complemento de Comercio Exterior 2.0
 */
class ComercioExteriorStrategy implements ComplementStrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function generateXML(DOMDocument $xml, DOMElement $complementoElement, object $complementData): void
    {
        $comercioExteriorElement = $xml->createElement('cce11:ComercioExterior');
        $comercioExteriorElement->setAttribute('Version', '2.0');
        
        // Solo agregar atributos si tienen valor
        if ($complementData->getMotivoTraslado()) {
            $comercioExteriorElement->setAttribute('MotivoTraslado', $complementData->getMotivoTraslado());
        }
        if ($complementData->getTipoOperacion()) {
            $comercioExteriorElement->setAttribute('TipoOperacion', $complementData->getTipoOperacion());
        }
        if ($complementData->getClavePedimento()) {
            $comercioExteriorElement->setAttribute('ClaveDePedimento', $complementData->getClavePedimento());
        }
        if ($complementData->getCertificadoOrigen()) {
            $comercioExteriorElement->setAttribute('CertificadoOrigen', $complementData->getCertificadoOrigen());
        }
        if ($complementData->getNumCertificadoOrigen()) {
            $comercioExteriorElement->setAttribute('NumCertificadoOrigen', $complementData->getNumCertificadoOrigen());
        }
        if ($complementData->getNumExportadorConfiable()) {
            $comercioExteriorElement->setAttribute('NumeroExportadorConfiable', $complementData->getNumExportadorConfiable());
        }
        if ($complementData->getIncoterm()) {
            $comercioExteriorElement->setAttribute('Incoterm', $complementData->getIncoterm());
        }
        if ($complementData->getSubdivision()) {
            $comercioExteriorElement->setAttribute('Subdivision', $complementData->getSubdivision());
        }
        if ($complementData->getObservaciones()) {
            $comercioExteriorElement->setAttribute('Observaciones', $complementData->getObservaciones());
        }
        if ($complementData->getTipoCambio()) {
            $comercioExteriorElement->setAttribute('TipoCambioUSD', $complementData->getTipoCambio());
        }
        if ($complementData->getTotalDolares()) {
            $comercioExteriorElement->setAttribute('TotalUSD', $complementData->getTotalDolares());
        }
        
        $complementoElement->appendChild($comercioExteriorElement);
    }
    
    /**
     * Agrega el emisor del comercio exterior
     */
    private function addEmisor(DOMDocument $xml, DOMElement $comercioExteriorElement, object $complementData): void
    {
        $emisor = $complementData->getEmisor();
        $emisorElement = $xml->createElement('cce11:Emisor');
        $emisorElement->setAttribute('Rfc', $emisor['rfc']);
        $emisorElement->setAttribute('Curp', $emisor['curp']);
        $emisorElement->setAttribute('Nombre', $emisor['nombre']);
        
        // Domicilio fiscal
        $domicilioElement = $xml->createElement('cce11:Domicilio');
        $domicilioElement->setAttribute('Calle', $emisor['domicilio']['calle']);
        $domicilioElement->setAttribute('NumeroExterior', $emisor['domicilio']['numeroExterior']);
        $domicilioElement->setAttribute('Colonia', $emisor['domicilio']['colonia']);
        $domicilioElement->setAttribute('Localidad', $emisor['domicilio']['localidad']);
        $domicilioElement->setAttribute('Municipio', $emisor['domicilio']['municipio']);
        $domicilioElement->setAttribute('Estado', $emisor['domicilio']['estado']);
        $domicilioElement->setAttribute('Pais', $emisor['domicilio']['pais']);
        $domicilioElement->setAttribute('CodigoPostal', $emisor['domicilio']['codigoPostal']);
        
        $emisorElement->appendChild($domicilioElement);
        $comercioExteriorElement->appendChild($emisorElement);
    }
    
    /**
     * Agrega el receptor del comercio exterior
     */
    private function addReceptor(DOMDocument $xml, DOMElement $comercioExteriorElement, object $complementData): void
    {
        $receptor = $complementData->getReceptor();
        $receptorElement = $xml->createElement('cce11:Receptor');
        $receptorElement->setAttribute('Rfc', $receptor['rfc']);
        $receptorElement->setAttribute('Curp', $receptor['curp']);
        $receptorElement->setAttribute('Nombre', $receptor['nombre']);
        $receptorElement->setAttribute('NumRegIdTrib', $receptor['numRegIdTrib']);
        $receptorElement->setAttribute('ResidenciaFiscal', $receptor['residenciaFiscal']);
        
        // Domicilio fiscal
        $domicilioElement = $xml->createElement('cce11:Domicilio');
        $domicilioElement->setAttribute('Calle', $receptor['domicilio']['calle']);
        $domicilioElement->setAttribute('NumeroExterior', $receptor['domicilio']['numeroExterior']);
        $domicilioElement->setAttribute('Colonia', $receptor['domicilio']['colonia']);
        $domicilioElement->setAttribute('Localidad', $receptor['domicilio']['localidad']);
        $domicilioElement->setAttribute('Municipio', $receptor['domicilio']['municipio']);
        $domicilioElement->setAttribute('Estado', $receptor['domicilio']['estado']);
        $domicilioElement->setAttribute('Pais', $receptor['domicilio']['pais']);
        $domicilioElement->setAttribute('CodigoPostal', $receptor['domicilio']['codigoPostal']);
        
        $receptorElement->appendChild($domicilioElement);
        $comercioExteriorElement->appendChild($receptorElement);
    }
    
    /**
     * Agrega el destinatario
     */
    private function addDestinatario(DOMDocument $xml, DOMElement $comercioExteriorElement, object $complementData): void
    {
        $destinatario = $complementData->getDestinatario();
        if (empty($destinatario)) {
            return;
        }
        
        $destinatarioElement = $xml->createElement('cce11:Destinatario');
        $destinatarioElement->setAttribute('Rfc', $destinatario['rfc']);
        $destinatarioElement->setAttribute('Curp', $destinatario['curp']);
        $destinatarioElement->setAttribute('Nombre', $destinatario['nombre']);
        $destinatarioElement->setAttribute('NumRegIdTrib', $destinatario['numRegIdTrib']);
        $destinatarioElement->setAttribute('ResidenciaFiscal', $destinatario['residenciaFiscal']);
        
        // Domicilio fiscal
        $domicilioElement = $xml->createElement('cce11:Domicilio');
        $domicilioElement->setAttribute('Calle', $destinatario['domicilio']['calle']);
        $domicilioElement->setAttribute('NumeroExterior', $destinatario['domicilio']['numeroExterior']);
        $domicilioElement->setAttribute('Colonia', $destinatario['domicilio']['colonia']);
        $domicilioElement->setAttribute('Localidad', $destinatario['domicilio']['localidad']);
        $domicilioElement->setAttribute('Municipio', $destinatario['domicilio']['municipio']);
        $domicilioElement->setAttribute('Estado', $destinatario['domicilio']['estado']);
        $domicilioElement->setAttribute('Pais', $destinatario['domicilio']['pais']);
        $domicilioElement->setAttribute('CodigoPostal', $destinatario['domicilio']['codigoPostal']);
        
        $destinatarioElement->appendChild($domicilioElement);
        $comercioExteriorElement->appendChild($destinatarioElement);
    }
    
    /**
     * Agrega las mercancías
     */
    private function addMercancias(DOMDocument $xml, DOMElement $comercioExteriorElement, object $complementData): void
    {
        $mercancias = $complementData->getMercancias();
        if (empty($mercancias)) {
            return;
        }
        
        $mercanciasElement = $xml->createElement('cce11:Mercancias');
        
        foreach ($mercancias as $mercancia) {
            $mercanciaElement = $xml->createElement('cce11:Mercancia');
            $mercanciaElement->setAttribute('NoIdentificacion', $mercancia['noIdentificacion']);
            $mercanciaElement->setAttribute('FraccionArancelaria', $mercancia['fraccionArancelaria']);
            $mercanciaElement->setAttribute('CantidadAduana', $mercancia['cantidadAduana']);
            $mercanciaElement->setAttribute('UnidadAduana', $mercancia['unidadAduana']);
            $mercanciaElement->setAttribute('ValorUnitarioAduana', $mercancia['valorUnitarioAduana']);
            $mercanciaElement->setAttribute('ValorDolares', $mercancia['valorDolares']);
            
            $mercanciasElement->appendChild($mercanciaElement);
        }
        
        $comercioExteriorElement->appendChild($mercanciasElement);
    }

    /**
     * {@inheritdoc}
     */
    public function validate(object $complementData): array
    {
        $errors = [];
        
        if (empty($complementData->getMotivoTraslado())) {
            $errors[] = 'El motivo de traslado es requerido';
        }
        
        if (empty($complementData->getTipoOperacion())) {
            $errors[] = 'El tipo de operación es requerido';
        }
        
        if (empty($complementData->getEmisor())) {
            $errors[] = 'La información del emisor es requerida';
        }
        
        if (empty($complementData->getReceptor())) {
            $errors[] = 'La información del receptor es requerida';
        }
        
        return $errors;
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace(): string
    {
        return 'http://www.sat.gob.mx/ComercioExterior11';
    }

    /**
     * {@inheritdoc}
     */
    public function getSchemaLocation(): string
    {
        return 'http://www.sat.gob.mx/ComercioExterior11 http://www.sat.gob.mx/sitio_internet/cfd/ComercioExterior11/ComercioExterior11.xsd';
    }

    /**
     * {@inheritdoc}
     */
    public function getComplementName(): string
    {
        return 'cce11';
    }
}
