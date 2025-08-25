<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Strategies\Pagos\Models;

/**
 * Modelo para los impuestos de un pago en el complemento de Pagos
 */
class ImpuestosPago
{
    private array $traslados = [];
    private array $retenciones = [];
    
    public function getTraslados(): array
    {
        return $this->traslados;
    }
    
    public function setTraslados(array $traslados): self
    {
        $this->traslados = $traslados;
        return $this;
    }
    
    public function addTraslado(array $traslado): self
    {
        $this->traslados[] = $traslado;
        return $this;
    }
    
    public function getRetenciones(): array
    {
        return $this->retenciones;
    }
    
    public function setRetenciones(array $retenciones): self
    {
        $this->retenciones = $retenciones;
        return $this;
    }
    
    public function addRetencion(array $retencion): self
    {
        $this->retenciones[] = $retencion;
        return $this;
    }
}
