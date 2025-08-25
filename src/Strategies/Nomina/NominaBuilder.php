<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Strategies\Nomina;

use AlfredoMejia\CfdiPhp\Builders\AbstractCFDIBuilder;
use AlfredoMejia\CfdiPhp\Strategies\Nomina\Models\NominaData;

/**
 * Builder específico para CFDI con complemento de Nómina
 */
class NominaBuilder extends AbstractCFDIBuilder
{
    private NominaData $nominaData;
    
    public function __construct($xmlGenerator = null, $validationService = null)
    {
        parent::__construct($xmlGenerator, $validationService);
        $this->nominaData = new NominaData();
    }
    
    /**
     * Configurar tipo de nómina
     */
    public function tipoNomina(string $tipo): self
    {
        $this->nominaData->setTipoNomina($tipo);
        return $this;
    }
    
    /**
     * Configurar fechas de pago
     */
    public function fechasPago(
        string $fechaPago,
        string $fechaInicialPago,
        string $fechaFinalPago,
        string $numDiasPagados
    ): self {
        $this->nominaData->setFechaPago($fechaPago);
        $this->nominaData->setFechaInicialPago($fechaInicialPago);
        $this->nominaData->setFechaFinalPago($fechaFinalPago);
        $this->nominaData->setNumDiasPagados($numDiasPagados);
        return $this;
    }
    
    /**
     * Configurar emisor de nómina
     */
    public function emisorNomina(array $datos): self
    {
        $this->nominaData->setEmisor($datos);
        return $this;
    }
    
    /**
     * Configurar receptor de nómina
     */
    public function receptorNomina(array $datos): self
    {
        $this->nominaData->setReceptor($datos);
        return $this;
    }
    
    /**
     * Configurar percepciones
     */
    public function percepciones(array $datos): self
    {
        $this->nominaData->setPercepciones($datos);
        return $this;
    }
    
    /**
     * Configurar deducciones
     */
    public function deducciones(array $datos): self
    {
        $this->nominaData->setDeducciones($datos);
        return $this;
    }
    
    /**
     * Configurar otros pagos
     */
    public function otrosPagos(array $datos): self
    {
        $this->nominaData->setOtrosPagos($datos);
        return $this;
    }
    
    /**
     * Configurar incapacidades
     */
    public function incapacidades(array $datos): self
    {
        $this->nominaData->setIncapacidades($datos);
        return $this;
    }
    
    /**
     * Configuración específica para CFDI con Nómina
     */
    protected function configureSpecific(): void
    {
        // Los CFDI con Nómina mantienen su tipo original
        // pero se agrega el complemento
        
        // Agregar los datos del complemento de Nómina
        $this->cfdiData->setComplemento($this->nominaData);
    }
    
    /**
     * Obtener los datos del complemento de Nómina
     */
    public function getNominaData(): NominaData
    {
        return $this->nominaData;
    }
}
