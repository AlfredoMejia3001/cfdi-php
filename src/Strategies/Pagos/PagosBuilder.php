<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Strategies\Pagos;

use AlfredoMejia\CfdiPhp\Builders\AbstractCFDIBuilder;
use AlfredoMejia\CfdiPhp\Strategies\Pagos\Models\PagosData;
use AlfredoMejia\CfdiPhp\Strategies\Pagos\Models\Pago;
use AlfredoMejia\CfdiPhp\Strategies\Pagos\Models\DoctoRelacionado;
use AlfredoMejia\CfdiPhp\Strategies\Pagos\Models\ImpuestosPago;

/**
 * Builder específico para CFDI con complemento de Pagos
 */
class PagosBuilder extends AbstractCFDIBuilder
{
    private PagosData $pagosData;
    private int $currentPagoIndex = -1;
    
    public function __construct($xmlGenerator = null, $validationService = null)
    {
        parent::__construct($xmlGenerator, $validationService);
        $this->pagosData = new PagosData();
    }
    
    /**
     * Agregar un pago al complemento
     */
    public function pago(
        string $fechaPago,
        string $formaDePagoP,
        string $monto,
        string $monedaP,
        ?string $tipoCambioP = null
    ): self {
        $pago = new Pago();
        $pago->setFechaPago($fechaPago)
             ->setFormaDePagoP($formaDePagoP)
             ->setMonto($monto)
             ->setMonedaP($monedaP);
             
        if ($tipoCambioP) {
            $pago->setTipoCambioP($tipoCambioP);
        }
        
        $this->pagosData->addPago($pago);
        $this->currentPagoIndex++;
        
        return $this;
    }
    
    /**
     * Agregar documento relacionado al último pago agregado
     */
    public function doctoRelacionado(
        string $idDocumento,
        string $monedaDR,
        string $metodoDePagoDR,
        string $numParcialidad,
        string $impSaldoAnt,
        string $impPagado,
        string $impSaldoInsoluto,
        ?string $tipoCambioDR = null
    ): self {
        if ($this->currentPagoIndex < 0) {
            throw new \InvalidArgumentException('Debe agregar un pago antes de agregar documentos relacionados');
        }
        
        $docto = new DoctoRelacionado();
        $docto->setIdDocumento($idDocumento)
              ->setMonedaDR($monedaDR)
              ->setMetodoDePagoDR($metodoDePagoDR)
              ->setNumParcialidad($numParcialidad)
              ->setImpSaldoAnt($impSaldoAnt)
              ->setImpPagado($impPagado)
              ->setImpSaldoInsoluto($impSaldoInsoluto);
              
        if ($tipoCambioDR) {
            $docto->setTipoCambioDR($tipoCambioDR);
        }
        
        $pagos = $this->pagosData->getPagos();
        $pagos[$this->currentPagoIndex]->addDocumentoRelacionado($docto);
        
        return $this;
    }
    
    /**
     * Agregar traslado al último documento relacionado agregado
     */
    public function trasladoDR(
        string $base,
        string $impuesto,
        string $tipoFactor,
        string $tasaOCuota,
        string $importe
    ): self {
        if ($this->currentPagoIndex < 0) {
            throw new \InvalidArgumentException('Debe agregar un pago antes de agregar traslados');
        }
        
        $traslado = [
            'base' => $base,
            'impuesto' => $impuesto,
            'tipoFactor' => $tipoFactor,
            'tasaOCuota' => $tasaOCuota,
            'importe' => $importe
        ];
        
        $pagos = $this->pagosData->getPagos();
        $documentos = $pagos[$this->currentPagoIndex]->getDocumentosRelacionados();
        
        if (empty($documentos)) {
            throw new \InvalidArgumentException('Debe agregar un documento relacionado antes de agregar traslados');
        }
        
        $ultimoDocumento = end($documentos);
        $ultimoDocumento->addTrasladoDR($traslado);
        
        return $this;
    }
    
    /**
     * Agregar retención al último documento relacionado agregado
     */
    public function retencionDR(
        string $impuesto,
        string $importe
    ): self {
        if ($this->currentPagoIndex < 0) {
            throw new \InvalidArgumentException('Debe agregar un pago antes de agregar retenciones');
        }
        
        $retencion = [
            'impuesto' => $impuesto,
            'importe' => $importe
        ];
        
        $pagos = $this->pagosData->getPagos();
        $documentos = $pagos[$this->currentPagoIndex]->getDocumentosRelacionados();
        
        if (empty($documentos)) {
            throw new \InvalidArgumentException('Debe agregar un documento relacionado antes de agregar retenciones');
        }
        
        $ultimoDocumento = end($documentos);
        $ultimoDocumento->addRetencionDR($retencion);
        
        return $this;
    }
    
    /**
     * Establecer totales de impuestos del último documento relacionado
     */
    public function impuestosTotalesDR(
        ?string $totalImpuestosRetenidos = null,
        ?string $totalImpuestosTrasladados = null
    ): self {
        if ($this->currentPagoIndex < 0) {
            throw new \InvalidArgumentException('Debe agregar un pago antes de establecer totales');
        }
        
        $totales = [];
        if ($totalImpuestosRetenidos) {
            $totales['totalImpuestosRetenidos'] = $totalImpuestosRetenidos;
        }
        if ($totalImpuestosTrasladados) {
            $totales['totalImpuestosTrasladados'] = $totalImpuestosTrasladados;
        }
        
        $pagos = $this->pagosData->getPagos();
        $documentos = $pagos[$this->currentPagoIndex]->getDocumentosRelacionados();
        
        if (empty($documentos)) {
            throw new \InvalidArgumentException('Debe agregar un documento relacionado antes de establecer totales');
        }
        
        $ultimoDocumento = end($documentos);
        $ultimoDocumento->setTotalesDR($totales);
        
        return $this;
    }
    
    /**
     * Agregar traslado a nivel del pago (totales del pago)
     */
    public function trasladoTotalDR(
        string $base,
        string $impuesto,
        string $tipoFactor,
        string $tasaOCuota,
        string $importe
    ): self {
        if ($this->currentPagoIndex < 0) {
            throw new \InvalidArgumentException('Debe agregar un pago antes de agregar traslados totales');
        }
        
        // Los traslados totales se pueden agregar a nivel del pago
        // Por ahora los guardamos como metadatos del pago
        $pagos = $this->pagosData->getPagos();
        $pagoActual = $pagos[$this->currentPagoIndex];
        
        // Si el pago no tiene impuestos, crear un objeto ImpuestosPago
        if (!$pagoActual->getImpuestos()) {
            $pagoActual->setImpuestos(new ImpuestosPago());
        }
        
        $traslado = [
            'base' => $base,
            'impuesto' => $impuesto,
            'tipoFactor' => $tipoFactor,
            'tasaOCuota' => $tasaOCuota,
            'importe' => $importe
        ];
        
        $impuestos = $pagoActual->getImpuestos();
        $impuestos->addTraslado($traslado);
        
        return $this;
    }
    
    /**
     * Configuración específica para CFDI de pagos
     */
    protected function configureSpecific(): void
    {
        // Los CFDI de pagos tienen características específicas
        $this->cfdiData->getComprobante()
            ->setTipoDeComprobante('P') // Pago
            ->setSubTotal('0.00')       // Siempre 0 en pagos
            ->setTotal('0.00')          // Siempre 0 en pagos
            ->setMoneda('XXX')          // Moneda XXX para pagos
            ->setExportacion('01');     // No aplica
            
        // Agregar los datos del complemento de pagos a los datos del CFDI
        $this->cfdiData->setComplemento($this->pagosData);
    }
    
    /**
     * Obtener los datos del complemento de pagos
     */
    public function getPagosData(): PagosData
    {
        return $this->pagosData;
    }
    
    /**
     * Método estático para crear rápidamente un CFDI de pagos
     */
    public static function createPago(
        string $fechaPago,
        string $formaDePagoP,
        string $monto,
        string $idDocumento
    ): self {
        return (new self())
            ->comprobante(
                noCertificado: '30001000000500003416',
                tipoDeComprobante: 'P',
                subTotal: '0.00',
                total: '0.00',
                moneda: 'XXX'
            )
            ->pago($fechaPago, $formaDePagoP, $monto)
            ->doctoRelacionado(
                idDocumento: $idDocumento,
                monedaDR: 'MXN',
                metodoDePagoDR: 'PPD',
                numParcialidad: '1',
                impSaldoAnt: $monto,
                impPagado: $monto,
                impSaldoInsoluto: '0.00'
            );
    }
}
