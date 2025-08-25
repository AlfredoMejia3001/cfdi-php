<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Strategies\Pagos;

use AlfredoMejia\CfdiPhp\Contracts\ComplementStrategyInterface;
use DOMDocument;
use DOMElement;

/**
 * Estrategia para el complemento de Pagos v2.0
 */
class PagosStrategy implements ComplementStrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function generateXML(DOMDocument $xml, DOMElement $complementoElement, object $complementData): void
    {
        // Crear elemento raíz del complemento de pagos
        $pagosElement = $xml->createElement('pago20:Pagos');
        $pagosElement->setAttribute('Version', '2.0');
        
        // Agregar cada pago
        foreach ($complementData->getPagos() as $pago) {
            $pagoElement = $this->createPagoElement($xml, $pago);
            $pagosElement->appendChild($pagoElement);
        }
        
        $complementoElement->appendChild($pagosElement);
    }
    
    /**
     * Crea un elemento Pago
     */
    private function createPagoElement(DOMDocument $xml, object $pago): DOMElement
    {
        $pagoElement = $xml->createElement('pago20:Pago');
        $pagoElement->setAttribute('FechaPago', $pago->getFechaPago());
        $pagoElement->setAttribute('FormaDePagoP', $pago->getFormaDePagoP());
        $pagoElement->setAttribute('MonedaP', $pago->getMonedaP());
        
        if ($pago->getTipoCambioP()) {
            $pagoElement->setAttribute('TipoCambioP', $pago->getTipoCambioP());
        }
        
        $pagoElement->setAttribute('Monto', $pago->getMonto());
        
        // Agregar documentos relacionados
        foreach ($pago->getDocumentosRelacionados() as $docto) {
            $doctoElement = $this->createDoctoRelacionadoElement($xml, $docto);
            $pagoElement->appendChild($doctoElement);
        }
        
        // Agregar impuestos del pago si existen
        if ($pago->getImpuestos()) {
            $impuestosElement = $this->createImpuestosPagoElement($xml, $pago->getImpuestos());
            $pagoElement->appendChild($impuestosElement);
        }
        
        return $pagoElement;
    }
    
    /**
     * Crea un elemento DoctoRelacionado
     */
    private function createDoctoRelacionadoElement(DOMDocument $xml, object $docto): DOMElement
    {
        $doctoElement = $xml->createElement('pago20:DoctoRelacionado');
        $doctoElement->setAttribute('IdDocumento', $docto->getIdDocumento());
        $doctoElement->setAttribute('MonedaDR', $docto->getMonedaDR());
        $doctoElement->setAttribute('MetodoDePagoDR', $docto->getMetodoDePagoDR());
        $doctoElement->setAttribute('NumParcialidad', $docto->getNumParcialidad());
        $doctoElement->setAttribute('ImpSaldoAnt', $docto->getImpSaldoAnt());
        $doctoElement->setAttribute('ImpPagado', $docto->getImpPagado());
        $doctoElement->setAttribute('ImpSaldoInsoluto', $docto->getImpSaldoInsoluto());
        
        if ($docto->getTipoCambioDR()) {
            $doctoElement->setAttribute('TipoCambioDR', $docto->getTipoCambioDR());
        }
        
        // Agregar impuestos del documento relacionado si existen
        $trasladosDR = $docto->getTrasladosDR();
        $retencionesDR = $docto->getRetencionesDR();
        $totalesDR = $docto->getTotalesDR();
        
        if (!empty($trasladosDR) || !empty($retencionesDR) || $totalesDR) {
            $impuestosDRElement = $xml->createElement('pago20:ImpuestosDR');
            
            // Agregar totales si existen
            if ($totalesDR) {
                if (isset($totalesDR['totalImpuestosRetenidos'])) {
                    $impuestosDRElement->setAttribute('TotalImpuestosRetenidosDR', $totalesDR['totalImpuestosRetenidos']);
                }
                if (isset($totalesDR['totalImpuestosTrasladados'])) {
                    $impuestosDRElement->setAttribute('TotalImpuestosTrasladosDR', $totalesDR['totalImpuestosTrasladados']);
                }
            }
            
            // Agregar retenciones
            if (!empty($retencionesDR)) {
                $retencionesDRElement = $xml->createElement('pago20:RetencionesDR');
                foreach ($retencionesDR as $retencion) {
                    $retencionDRElement = $xml->createElement('pago20:RetencionDR');
                    $retencionDRElement->setAttribute('ImpuestoDR', $retencion['impuesto']);
                    $retencionDRElement->setAttribute('ImporteDR', $retencion['importe']);
                    $retencionesDRElement->appendChild($retencionDRElement);
                }
                $impuestosDRElement->appendChild($retencionesDRElement);
            }
            
            // Agregar traslados
            if (!empty($trasladosDR)) {
                $trasladosDRElement = $xml->createElement('pago20:TrasladosDR');
                foreach ($trasladosDR as $traslado) {
                    $trasladoDRElement = $xml->createElement('pago20:TrasladoDR');
                    $trasladoDRElement->setAttribute('BaseDR', $traslado['base']);
                    $trasladoDRElement->setAttribute('ImpuestoDR', $traslado['impuesto']);
                    $trasladoDRElement->setAttribute('TipoFactorDR', $traslado['tipoFactor']);
                    $trasladoDRElement->setAttribute('TasaOCuotaDR', $traslado['tasaOCuota']);
                    $trasladoDRElement->setAttribute('ImporteDR', $traslado['importe']);
                    $trasladosDRElement->appendChild($trasladoDRElement);
                }
                $impuestosDRElement->appendChild($trasladosDRElement);
            }
            
            $doctoElement->appendChild($impuestosDRElement);
        }
        
        return $doctoElement;
    }
    
    /**
     * Crea elemento de impuestos del pago
     */
    private function createImpuestosPagoElement(DOMDocument $xml, object $impuestos): DOMElement
    {
        $impuestosElement = $xml->createElement('pago20:ImpuestosP');
        
        // Retenciones
        $retenciones = $impuestos->getRetenciones();
        if (!empty($retenciones)) {
            $retencionesElement = $xml->createElement('pago20:RetencionesP');
            foreach ($retenciones as $retencion) {
                $retencionElement = $xml->createElement('pago20:RetencionP');
                $retencionElement->setAttribute('ImpuestoP', $retencion['impuesto']);
                $retencionElement->setAttribute('ImporteP', $retencion['importe']);
                $retencionesElement->appendChild($retencionElement);
            }
            $impuestosElement->appendChild($retencionesElement);
        }
        
        // Traslados
        $traslados = $impuestos->getTraslados();
        if (!empty($traslados)) {
            $trasladosElement = $xml->createElement('pago20:TrasladosP');
            foreach ($traslados as $traslado) {
                $trasladoElement = $xml->createElement('pago20:TrasladoP');
                $trasladoElement->setAttribute('BaseP', $traslado['base']);
                $trasladoElement->setAttribute('ImpuestoP', $traslado['impuesto']);
                $trasladoElement->setAttribute('TipoFactorP', $traslado['tipoFactor']);
                $trasladoElement->setAttribute('TasaOCuotaP', $traslado['tasaOCuota']);
                $trasladoElement->setAttribute('ImporteP', $traslado['importe']);
                $trasladosElement->appendChild($trasladoElement);
            }
            $impuestosElement->appendChild($trasladosElement);
        }
        
        return $impuestosElement;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(object $complementData): array
    {
        $errors = [];
        
        if (empty($complementData->getPagos())) {
            $errors[] = 'Se requiere al menos un pago en el complemento';
            return $errors;
        }
        
        foreach ($complementData->getPagos() as $index => $pago) {
            if (empty($pago->getFechaPago())) {
                $errors[] = "FechaPago es requerida en el pago " . ($index + 1);
            }
            
            if (empty($pago->getFormaDePagoP())) {
                $errors[] = "FormaDePagoP es requerida en el pago " . ($index + 1);
            }
            
            if (empty($pago->getMonto()) || (float)$pago->getMonto() <= 0) {
                $errors[] = "Monto debe ser mayor a cero en el pago " . ($index + 1);
            }
            
            if (empty($pago->getDocumentosRelacionados())) {
                $errors[] = "Se requiere al menos un documento relacionado en el pago " . ($index + 1);
            }
        }
        
        return $errors;
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace(): string
    {
        return 'http://www.sat.gob.mx/Pagos20';
    }

    /**
     * {@inheritdoc}
     */
    public function getSchemaLocation(): string
    {
        return 'http://www.sat.gob.mx/Pagos20 http://www.sat.gob.mx/sitio_internet/cfd/Pagos/Pagos20.xsd';
    }

    /**
     * {@inheritdoc}
     */
    public function getComplementName(): string
    {
        return 'pago20';
    }
}
