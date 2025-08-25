<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Strategies\Pagos\Models;

/**
 * Modelo para un pago individual del complemento de Pagos
 */
class Pago
{
    private string $fechaPago;
    private string $formaDePagoP;
    private string $monedaP;
    private ?string $tipoCambioP = null;
    private string $monto;
    private array $documentosRelacionados = [];
    private ?ImpuestosPago $impuestos = null;
    
    public function getFechaPago(): string
    {
        return $this->fechaPago;
    }
    
    public function setFechaPago(string $fechaPago): self
    {
        $this->fechaPago = $fechaPago;
        return $this;
    }
    
    public function getFormaDePagoP(): string
    {
        return $this->formaDePagoP;
    }
    
    public function setFormaDePagoP(string $formaDePagoP): self
    {
        $this->formaDePagoP = $formaDePagoP;
        return $this;
    }
    
    public function getMonedaP(): string
    {
        return $this->monedaP;
    }
    
    public function setMonedaP(string $monedaP): self
    {
        $this->monedaP = $monedaP;
        return $this;
    }
    
    public function getTipoCambioP(): ?string
    {
        return $this->tipoCambioP;
    }
    
    public function setTipoCambioP(?string $tipoCambioP): self
    {
        $this->tipoCambioP = $tipoCambioP;
        return $this;
    }
    
    public function getMonto(): string
    {
        return $this->monto;
    }
    
    public function setMonto(string $monto): self
    {
        $this->monto = $monto;
        return $this;
    }
    
    public function getDocumentosRelacionados(): array
    {
        return $this->documentosRelacionados;
    }
    
    public function addDocumentoRelacionado(DoctoRelacionado $docto): self
    {
        $this->documentosRelacionados[] = $docto;
        return $this;
    }
    
    public function setDocumentosRelacionados(array $documentos): self
    {
        $this->documentosRelacionados = $documentos;
        return $this;
    }
    
    public function getImpuestos(): ?ImpuestosPago
    {
        return $this->impuestos;
    }
    
    public function setImpuestos(?ImpuestosPago $impuestos): self
    {
        $this->impuestos = $impuestos;
        return $this;
    }
}
