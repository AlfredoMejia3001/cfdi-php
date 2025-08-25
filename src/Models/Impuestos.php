<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Models;

/**
 * Modelo para los impuestos totales del CFDI
 */
class Impuestos
{
    private ?string $totalImpuestosRetenidos = null;
    private ?string $totalImpuestosTrasladados = null;
    private array $retenciones = [];
    private array $traslados = [];

    public function getTotalImpuestosRetenidos(): ?string
    {
        return $this->totalImpuestosRetenidos;
    }

    public function setTotalImpuestosRetenidos(?string $totalImpuestosRetenidos): self
    {
        $this->totalImpuestosRetenidos = $totalImpuestosRetenidos;
        return $this;
    }

    public function getTotalImpuestosTrasladados(): ?string
    {
        return $this->totalImpuestosTrasladados;
    }

    public function setTotalImpuestosTrasladados(?string $totalImpuestosTrasladados): self
    {
        $this->totalImpuestosTrasladados = $totalImpuestosTrasladados;
        return $this;
    }

    public function getRetenciones(): array
    {
        return $this->retenciones;
    }

    public function addRetencion(string $impuesto, string $importe): self
    {
        $this->retenciones[] = [
            'impuesto' => $impuesto,
            'importe' => $importe
        ];
        return $this;
    }

    public function getTraslados(): array
    {
        return $this->traslados;
    }

    public function addTraslado(
        string $base, 
        string $impuesto, 
        string $tipoFactor, 
        string $tasaOCuota, 
        string $importe
    ): self {
        $this->traslados[] = [
            'base' => $base,
            'impuesto' => $impuesto,
            'tipoFactor' => $tipoFactor,
            'tasaOCuota' => $tasaOCuota,
            'importe' => $importe
        ];
        return $this;
    }
}
