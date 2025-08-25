<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Builders;

use AlfredoMejia\CfdiPhp\Contracts\CFDIBuilderInterface;
use AlfredoMejia\CfdiPhp\Models\CFDIData;
use AlfredoMejia\CfdiPhp\Models\Comprobante;
use AlfredoMejia\CfdiPhp\Models\Emisor;
use AlfredoMejia\CfdiPhp\Models\Receptor;
use AlfredoMejia\CfdiPhp\Models\Concepto;

/**
 * Builder base abstracto para CFDI
 * Implementa la funcionalidad común a todos los builders
 */
abstract class AbstractCFDIBuilder implements CFDIBuilderInterface
{
    protected CFDIData $cfdiData;

    public function __construct()
    {
        $this->reset();
    }

    /**
     * {@inheritdoc}
     */
    public function comprobante(
        string $noCertificado,
        ?string $serie = null,
        ?string $folio = null,
        ?string $fecha = null,
        string $subTotal = '0.00',
        string $moneda = 'MXN',
        string $total = '0.00',
        string $tipoDeComprobante = 'I',
        string $exportacion = '01',
        ?string $metodoPago = null,
        ?string $formaPago = null,
        string $lugarExpedicion = '06500'
    ): CFDIBuilderInterface {
        $comprobante = $this->cfdiData->getComprobante();
        
        $comprobante
            ->setNoCertificado($noCertificado)
            ->setSerie($serie)
            ->setFolio($folio)
            ->setFecha($fecha ?: date('Y-m-d\TH:i:s'))
            ->setSubTotal($subTotal)
            ->setMoneda($moneda)
            ->setTotal($total)
            ->setTipoDeComprobante($tipoDeComprobante)
            ->setExportacion($exportacion)
            ->setMetodoPago($metodoPago)
            ->setFormaPago($formaPago)
            ->setLugarExpedicion($lugarExpedicion);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function emisor(
        string $rfc,
        string $nombre,
        string $regimenFiscal,
        ?string $facAtrAdquiriente = null
    ): CFDIBuilderInterface {
        $emisor = $this->cfdiData->getEmisor();
        
        $emisor
            ->setRfc($rfc)
            ->setNombre($nombre)
            ->setRegimenFiscal($regimenFiscal)
            ->setFacAtrAdquiriente($facAtrAdquiriente);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function receptor(
        string $rfc,
        string $nombre,
        string $regimenFiscalReceptor,
        string $usoCFDI,
        string $domicilioFiscalReceptor,
        ?string $residenciaFiscal = null,
        ?string $numRegIdTrib = null
    ): CFDIBuilderInterface {
        $receptor = $this->cfdiData->getReceptor();
        
        $receptor
            ->setRfc($rfc)
            ->setNombre($nombre)
            ->setRegimenFiscalReceptor($regimenFiscalReceptor)
            ->setUsoCFDI($usoCFDI)
            ->setDomicilioFiscalReceptor($domicilioFiscalReceptor)
            ->setResidenciaFiscal($residenciaFiscal)
            ->setNumRegIdTrib($numRegIdTrib);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function concepto(
        string $claveProdServ,
        string $cantidad,
        string $claveUnidad,
        string $descripcion,
        string $valorUnitario,
        string $importe,
        string $objetoImp,
        ?string $noIdentificacion = null,
        ?string $unidad = null,
        ?string $descuento = null
    ): CFDIBuilderInterface {
        $concepto = new Concepto();
        
        $concepto
            ->setClaveProdServ($claveProdServ)
            ->setCantidad($cantidad)
            ->setClaveUnidad($claveUnidad)
            ->setDescripcion($descripcion)
            ->setValorUnitario($valorUnitario)
            ->setImporte($importe)
            ->setObjetoImp($objetoImp)
            ->setNoIdentificacion($noIdentificacion)
            ->setUnidad($unidad)
            ->setDescuento($descuento);

        $this->cfdiData->addConcepto($concepto);

        return $this;
    }

    /**
     * Agrega un traslado al último concepto agregado
     */
    public function traslado(
        string $base,
        string $impuesto,
        string $tipoFactor,
        string $tasaOCuota,
        string $importe
    ): CFDIBuilderInterface {
        $conceptos = $this->cfdiData->getConceptos();
        
        if (empty($conceptos)) {
            throw new \InvalidArgumentException('Debe agregar al menos un concepto antes de agregar traslados');
        }
        
        $ultimoConcepto = end($conceptos);
        $ultimoConcepto->addTraslado([
            'base' => $base,
            'impuesto' => $impuesto,
            'tipoFactor' => $tipoFactor,
            'tasaOCuota' => $tasaOCuota,
            'importe' => $importe
        ]);

        return $this;
    }

    /**
     * Agrega una retención al último concepto agregado
     */
    public function retencion(
        string $base,
        string $impuesto,
        string $tipoFactor,
        string $tasaOCuota,
        string $importe
    ): CFDIBuilderInterface {
        $conceptos = $this->cfdiData->getConceptos();
        
        if (empty($conceptos)) {
            throw new \InvalidArgumentException('Debe agregar al menos un concepto antes de agregar retenciones');
        }
        
        $ultimoConcepto = end($conceptos);
        $ultimoConcepto->addRetencion([
            'base' => $base,
            'impuesto' => $impuesto,
            'tipoFactor' => $tipoFactor,
            'tasaOCuota' => $tasaOCuota,
            'importe' => $importe
        ]);

        return $this;
    }

    /**
     * Agrega CFDI relacionados
     */
    public function cfdiRelacionado(string $uuid, string $tipoRelacion = '04'): CFDIBuilderInterface
    {
        $this->cfdiData->addCfdiRelacionado($uuid, $tipoRelacion);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build(): CFDIData
    {
        // Primero aplicar configuraciones específicas del tipo de CFDI
        $this->configureSpecific();
        
        // Luego validar
        $this->validate();
        
        return $this->cfdiData;
    }

    /**
     * {@inheritdoc}
     */
    public function reset(): CFDIBuilderInterface
    {
        $this->cfdiData = new CFDIData();
        return $this;
    }

    /**
     * Valida que los datos mínimos estén presentes
     */
    protected function validate(): void
    {
        $comprobante = $this->cfdiData->getComprobante();
        $emisor = $this->cfdiData->getEmisor();
        $receptor = $this->cfdiData->getReceptor();
        $conceptos = $this->cfdiData->getConceptos();

        $errors = [];

        // Validar comprobante
        try {
            if (empty($comprobante->getNoCertificado())) {
                $errors[] = 'El número de certificado es requerido';
            }
        } catch (\Error $e) {
            $errors[] = 'El número de certificado es requerido';
        }

        // Validar emisor
        try {
            if (empty($emisor->getRfc())) {
                $errors[] = 'El RFC del emisor es requerido';
            }
        } catch (\Error $e) {
            $errors[] = 'El RFC del emisor es requerido';
        }

        // Validar receptor  
        try {
            if (empty($receptor->getRfc())) {
                $errors[] = 'El RFC del receptor es requerido';
            }
        } catch (\Error $e) {
            $errors[] = 'El RFC del receptor es requerido';
        }

        // Validar conceptos (excepto para CFDI de Pagos que usan complementos)
        $tipoComprobante = $comprobante->getTipoDeComprobante();
        if (empty($conceptos) && $tipoComprobante !== 'P') {
            $errors[] = 'Se requiere al menos un concepto';
        }

        if (!empty($errors)) {
            throw new \InvalidArgumentException('Datos incompletos: ' . implode(', ', $errors));
        }
    }

    /**
     * Método template para configuraciones específicas del tipo de CFDI
     */
    abstract protected function configureSpecific(): void;
}
