<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Services;

use AlfredoMejia\CfdiPhp\Contracts\XMLGeneratorInterface;
use AlfredoMejia\CfdiPhp\Contracts\ComplementStrategyInterface;
use AlfredoMejia\CfdiPhp\Models\CFDIData;
use AlfredoMejia\CfdiPhp\Exceptions\CFDIException;
use DOMDocument;
use DOMElement;

/**
 * Servicio base para generación de XML de CFDI
 */
class XMLGeneratorService implements XMLGeneratorInterface
{
    protected ?ComplementStrategyInterface $complementStrategy = null;
    protected string $namespace = 'http://www.sat.gob.mx/cfd/4';
    protected string $schemaLocation = 'http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd';

    public function setComplementStrategy(?ComplementStrategyInterface $strategy): self
    {
        $this->complementStrategy = $strategy;
        
        // Si hay estrategia, actualizar schema location
        if ($strategy) {
            $this->schemaLocation .= ' ' . $strategy->getSchemaLocation();
        }
        
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(CFDIData $cfdiData): string
    {
        try {
            $xml = new DOMDocument('1.0', 'UTF-8');
            $xml->formatOutput = true;
            $xml->preserveWhiteSpace = false;

            // Crear elemento raíz
            $root = $this->createRootElement($xml);
            $xml->appendChild($root);

            // Agregar elementos principales
            $this->addComprobanteAttributes($root, $cfdiData->getComprobante());
            $this->addEmisor($xml, $root, $cfdiData->getEmisor());
            $this->addReceptor($xml, $root, $cfdiData->getReceptor());
            $this->addConceptos($xml, $root, $cfdiData->getConceptos());
            
            // Agregar impuestos si existen
            if ($cfdiData->getImpuestos()) {
                $this->addImpuestos($xml, $root, $cfdiData->getImpuestos());
            }

            // Agregar complemento si existe
            if ($cfdiData->getComplemento() && $this->complementStrategy) {
                $this->addComplemento($xml, $root, $cfdiData->getComplemento());
            }

            return $xml->saveXML();
            
        } catch (\Exception $e) {
            throw new CFDIException(
                'Error al generar XML: ' . $e->getMessage(),
                [],
                0,
                $e
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validateXML(string $xml): bool
    {
        // Para implementar en el futuro
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * {@inheritdoc}
     */
    public function getSchemaLocation(): string
    {
        return $this->schemaLocation;
    }

    /**
     * Crea el elemento raíz del XML
     */
    protected function createRootElement(DOMDocument $xml): DOMElement
    {
        $root = $xml->createElementNS($this->namespace, 'cfdi:Comprobante');
        $root->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $root->setAttribute('xmlns:cfdi', $this->namespace);
        
        // Agregar namespaces de complementos si existen
        if ($this->complementStrategy) {
            $complementNs = $this->complementStrategy->getNamespace();
            $complementName = $this->complementStrategy->getComplementName();
            $root->setAttribute("xmlns:{$complementName}", $complementNs);
        }
        
        $root->setAttribute('xsi:schemaLocation', $this->schemaLocation);
        
        return $root;
    }

    /**
     * Agrega los atributos del comprobante al elemento raíz
     */
    protected function addComprobanteAttributes(DOMElement $root, $comprobante): void
    {
        $root->setAttribute('Version', $comprobante->getVersion());
        
        if ($comprobante->getSerie()) {
            $root->setAttribute('Serie', $comprobante->getSerie());
        }
        
        if ($comprobante->getFolio()) {
            $root->setAttribute('Folio', $comprobante->getFolio());
        }
        
        $root->setAttribute('Fecha', $comprobante->getFecha());
        $root->setAttribute('Sello', ''); // Se llena durante el timbrado
        $root->setAttribute('NoCertificado', $comprobante->getNoCertificado());
        $root->setAttribute('Certificado', ''); // Se llena durante el timbrado
        
        if ($comprobante->getFormaPago()) {
            $root->setAttribute('FormaPago', $comprobante->getFormaPago());
        }
        
        if ($comprobante->getCondicionesDePago()) {
            $root->setAttribute('CondicionesDePago', $comprobante->getCondicionesDePago());
        }
        
        $root->setAttribute('SubTotal', $comprobante->getSubTotal());
        
        if ($comprobante->getDescuento()) {
            $root->setAttribute('Descuento', $comprobante->getDescuento());
        }
        
        $root->setAttribute('Moneda', $comprobante->getMoneda());
        
        if ($comprobante->getTipoCambio()) {
            $root->setAttribute('TipoCambio', $comprobante->getTipoCambio());
        }
        
        $root->setAttribute('Total', $comprobante->getTotal());
        $root->setAttribute('TipoDeComprobante', $comprobante->getTipoDeComprobante());
        
        if ($comprobante->getMetodoPago()) {
            $root->setAttribute('MetodoPago', $comprobante->getMetodoPago());
        }
        
        $root->setAttribute('LugarExpedicion', $comprobante->getLugarExpedicion());
        
        if ($comprobante->getConfirmacion()) {
            $root->setAttribute('Confirmacion', $comprobante->getConfirmacion());
        }
        
        $root->setAttribute('Exportacion', $comprobante->getExportacion());
    }

    /**
     * Agrega el elemento Emisor
     */
    protected function addEmisor(DOMDocument $xml, DOMElement $root, $emisor): void
    {
        $emisorElement = $xml->createElement('cfdi:Emisor');
        $emisorElement->setAttribute('Rfc', $emisor->getRfc());
        $emisorElement->setAttribute('Nombre', $emisor->getNombre());
        $emisorElement->setAttribute('RegimenFiscal', $emisor->getRegimenFiscal());
        
        if ($emisor->getFacAtrAdquiriente()) {
            $emisorElement->setAttribute('FacAtrAdquiriente', $emisor->getFacAtrAdquiriente());
        }
        
        $root->appendChild($emisorElement);
    }

    /**
     * Agrega el elemento Receptor
     */
    protected function addReceptor(DOMDocument $xml, DOMElement $root, $receptor): void
    {
        $receptorElement = $xml->createElement('cfdi:Receptor');
        $receptorElement->setAttribute('Rfc', $receptor->getRfc());
        $receptorElement->setAttribute('Nombre', $receptor->getNombre());
        $receptorElement->setAttribute('UsoCFDI', $receptor->getUsoCFDI());
        $receptorElement->setAttribute('DomicilioFiscalReceptor', $receptor->getDomicilioFiscalReceptor());
        $receptorElement->setAttribute('RegimenFiscalReceptor', $receptor->getRegimenFiscalReceptor());
        
        if ($receptor->getResidenciaFiscal()) {
            $receptorElement->setAttribute('ResidenciaFiscal', $receptor->getResidenciaFiscal());
        }
        
        if ($receptor->getNumRegIdTrib()) {
            $receptorElement->setAttribute('NumRegIdTrib', $receptor->getNumRegIdTrib());
        }
        
        $root->appendChild($receptorElement);
    }

    /**
     * Agrega los elementos Conceptos
     */
    protected function addConceptos(DOMDocument $xml, DOMElement $root, array $conceptos): void
    {
        $conceptosElement = $xml->createElement('cfdi:Conceptos');
        
        foreach ($conceptos as $concepto) {
            $conceptoElement = $xml->createElement('cfdi:Concepto');
            $conceptoElement->setAttribute('ClaveProdServ', $concepto->getClaveProdServ());
            
            if ($concepto->getNoIdentificacion()) {
                $conceptoElement->setAttribute('NoIdentificacion', $concepto->getNoIdentificacion());
            }
            
            $conceptoElement->setAttribute('Cantidad', $concepto->getCantidad());
            $conceptoElement->setAttribute('ClaveUnidad', $concepto->getClaveUnidad());
            
            if ($concepto->getUnidad()) {
                $conceptoElement->setAttribute('Unidad', $concepto->getUnidad());
            }
            
            $conceptoElement->setAttribute('Descripcion', $concepto->getDescripcion());
            $conceptoElement->setAttribute('ValorUnitario', $concepto->getValorUnitario());
            $conceptoElement->setAttribute('Importe', $concepto->getImporte());
            
            if ($concepto->getDescuento()) {
                $conceptoElement->setAttribute('Descuento', $concepto->getDescuento());
            }
            
            $conceptoElement->setAttribute('ObjetoImp', $concepto->getObjetoImp());
            
            $conceptosElement->appendChild($conceptoElement);
        }
        
        $root->appendChild($conceptosElement);
    }

    /**
     * Agrega el elemento Impuestos
     */
    protected function addImpuestos(DOMDocument $xml, DOMElement $root, $impuestos): void
    {
        $impuestosElement = $xml->createElement('cfdi:Impuestos');
        
        if ($impuestos->getTotalImpuestosRetenidos()) {
            $impuestosElement->setAttribute('TotalImpuestosRetenidos', $impuestos->getTotalImpuestosRetenidos());
        }
        
        if ($impuestos->getTotalImpuestosTrasladados()) {
            $impuestosElement->setAttribute('TotalImpuestosTrasladados', $impuestos->getTotalImpuestosTrasladados());
        }
        
        // Agregar retenciones
        $retenciones = $impuestos->getRetenciones();
        if (!empty($retenciones)) {
            $retencionesElement = $xml->createElement('cfdi:Retenciones');
            foreach ($retenciones as $retencion) {
                $retencionElement = $xml->createElement('cfdi:Retencion');
                $retencionElement->setAttribute('Impuesto', $retencion['impuesto']);
                $retencionElement->setAttribute('Importe', $retencion['importe']);
                $retencionesElement->appendChild($retencionElement);
            }
            $impuestosElement->appendChild($retencionesElement);
        }
        
        // Agregar traslados
        $traslados = $impuestos->getTraslados();
        if (!empty($traslados)) {
            $trasladosElement = $xml->createElement('cfdi:Traslados');
            foreach ($traslados as $traslado) {
                $trasladoElement = $xml->createElement('cfdi:Traslado');
                $trasladoElement->setAttribute('Base', $traslado['base']);
                $trasladoElement->setAttribute('Impuesto', $traslado['impuesto']);
                $trasladoElement->setAttribute('TipoFactor', $traslado['tipoFactor']);
                $trasladoElement->setAttribute('TasaOCuota', $traslado['tasaOCuota']);
                $trasladoElement->setAttribute('Importe', $traslado['importe']);
                $trasladosElement->appendChild($trasladoElement);
            }
            $impuestosElement->appendChild($trasladosElement);
        }
        
        $root->appendChild($impuestosElement);
    }

    /**
     * Agrega el elemento Complemento
     */
    protected function addComplemento(DOMDocument $xml, DOMElement $root, $complementData): void
    {
        if (!$this->complementStrategy) {
            return;
        }
        
        $complementoElement = $xml->createElement('cfdi:Complemento');
        $this->complementStrategy->generateXML($xml, $complementoElement, $complementData);
        $root->appendChild($complementoElement);
    }
}
