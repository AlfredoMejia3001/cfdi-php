<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Models;

/**
 * Modelo para los datos del receptor
 */
class Receptor
{
    private ?string $rfc = null;
    private ?string $nombre = null;
    private ?string $regimenFiscalReceptor = null;
    private ?string $usoCFDI = null;
    private ?string $domicilioFiscalReceptor = null;
    private ?string $residenciaFiscal = null;
    private ?string $numRegIdTrib = null;

    public function getRfc(): ?string
    {
        return $this->rfc;
    }

    public function setRfc(string $rfc): self
    {
        $this->rfc = $rfc;
        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getRegimenFiscalReceptor(): ?string
    {
        return $this->regimenFiscalReceptor;
    }

    public function setRegimenFiscalReceptor(string $regimenFiscalReceptor): self
    {
        $this->regimenFiscalReceptor = $regimenFiscalReceptor;
        return $this;
    }

    public function getUsoCFDI(): ?string
    {
        return $this->usoCFDI;
    }

    public function setUsoCFDI(string $usoCFDI): self
    {
        $this->usoCFDI = $usoCFDI;
        return $this;
    }

    public function getDomicilioFiscalReceptor(): ?string
    {
        return $this->domicilioFiscalReceptor;
    }

    public function setDomicilioFiscalReceptor(string $domicilioFiscalReceptor): self
    {
        $this->domicilioFiscalReceptor = $domicilioFiscalReceptor;
        return $this;
    }

    public function getResidenciaFiscal(): ?string
    {
        return $this->residenciaFiscal;
    }

    public function setResidenciaFiscal(?string $residenciaFiscal): self
    {
        $this->residenciaFiscal = $residenciaFiscal;
        return $this;
    }

    public function getNumRegIdTrib(): ?string
    {
        return $this->numRegIdTrib;
    }

    public function setNumRegIdTrib(?string $numRegIdTrib): self
    {
        $this->numRegIdTrib = $numRegIdTrib;
        return $this;
    }
}
