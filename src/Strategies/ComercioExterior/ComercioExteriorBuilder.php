<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Strategies\ComercioExterior;

use AlfredoMejia\CfdiPhp\Builders\AbstractCFDIBuilder;
use AlfredoMejia\CfdiPhp\Strategies\ComercioExterior\Models\ComercioExteriorData;

/**
 * Builder específico para CFDI con complemento de Comercio Exterior
 */
class ComercioExteriorBuilder extends AbstractCFDIBuilder
{
    private ComercioExteriorData $comercioExteriorData;
    
    public function __construct($xmlGenerator = null, $validationService = null)
    {
        parent::__construct($xmlGenerator, $validationService);
        $this->comercioExteriorData = new ComercioExteriorData();
    }
    
    /**
     * Configurar motivo de traslado
     */
    public function motivoTraslado(string $motivo): self
    {
        $this->comercioExteriorData->setMotivoTraslado($motivo);
        return $this;
    }
    
    /**
     * Configurar tipo de operación
     */
    public function tipoOperacion(string $tipo): self
    {
        $this->comercioExteriorData->setTipoOperacion($tipo);
        return $this;
    }
    
    /**
     * Configurar clave de pedimento
     */
    public function clavePedimento(string $clave): self
    {
        $this->comercioExteriorData->setClavePedimento($clave);
        return $this;
    }
    
    /**
     * Configurar certificado de origen
     */
    public function certificadoOrigen(string $certificado): self
    {
        $this->comercioExteriorData->setCertificadoOrigen($certificado);
        return $this;
    }
    
    /**
     * Configurar número de certificado de origen
     */
    public function numCertificadoOrigen(string $numero): self
    {
        $this->comercioExteriorData->setNumCertificadoOrigen($numero);
        return $this;
    }
    
    /**
     * Configurar número de exportador confiable
     */
    public function numExportadorConfiable(string $numero): self
    {
        $this->comercioExteriorData->setNumExportadorConfiable($numero);
        return $this;
    }
    
    /**
     * Configurar incoterm
     */
    public function incoterm(string $incoterm): self
    {
        $this->comercioExteriorData->setIncoterm($incoterm);
        return $this;
    }
    
    /**
     * Configurar subdivisión
     */
    public function subdivision(string $subdivision): self
    {
        $this->comercioExteriorData->setSubdivision($subdivision);
        return $this;
    }
    
    /**
     * Configurar observaciones
     */
    public function observaciones(string $observaciones): self
    {
        $this->comercioExteriorData->setObservaciones($observaciones);
        return $this;
    }
    
    /**
     * Configurar tipo de cambio
     */
    public function tipoCambio(string $tipoCambio): self
    {
        $this->comercioExteriorData->setTipoCambio($tipoCambio);
        return $this;
    }
    
    /**
     * Configurar total de dólares
     */
    public function totalDolares(string $total): self
    {
        $this->comercioExteriorData->setTotalDolares($total);
        return $this;
    }
    
    /**
     * Configuración específica para CFDI con Comercio Exterior
     */
    protected function configureSpecific(): void
    {
        // Los CFDI con Comercio Exterior mantienen su tipo original
        // pero se agrega el complemento
        
        // Agregar los datos del complemento de Comercio Exterior
        $this->cfdiData->setComplemento($this->comercioExteriorData);
    }
    
    /**
     * Obtener los datos del complemento de Comercio Exterior
     */
    public function getComercioExteriorData(): ComercioExteriorData
    {
        return $this->comercioExteriorData;
    }
}
