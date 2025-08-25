<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Strategies\Pagos\Models;

/**
 * Contenedor de datos del complemento de Pagos
 */
class PagosData
{
    private array $pagos = [];
    
    public function addPago(Pago $pago): self
    {
        $this->pagos[] = $pago;
        return $this;
    }
    
    public function getPagos(): array
    {
        return $this->pagos;
    }
    
    public function setPagos(array $pagos): self
    {
        $this->pagos = $pagos;
        return $this;
    }
}
