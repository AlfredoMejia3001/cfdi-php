<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Models;

/**
 * Modelo para los datos del emisor
 */
class Emisor
{
    private ?string $rfc = null;
    private ?string $nombre = null;
    private ?string $regimenFiscal = null;
    private ?string $facAtrAdquiriente = null;

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

    public function getRegimenFiscal(): ?string
    {
        return $this->regimenFiscal;
    }

    public function setRegimenFiscal(string $regimenFiscal): self
    {
        $this->regimenFiscal = $regimenFiscal;
        return $this;
    }

    public function getFacAtrAdquiriente(): ?string
    {
        return $this->facAtrAdquiriente;
    }

    public function setFacAtrAdquiriente(?string $facAtrAdquiriente): self
    {
        $this->facAtrAdquiriente = $facAtrAdquiriente;
        return $this;
    }
}
