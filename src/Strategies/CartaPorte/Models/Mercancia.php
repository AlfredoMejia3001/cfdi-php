<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Strategies\CartaPorte\Models;

/**
 * Modelo para mercancías en Carta Porte
 */
class Mercancia
{
    private string $bienesTransp;
    private string $descripcion;
    private string $cantidad;
    private string $claveUnidad;
    private string $pesoEnKg;
    private ?string $unidad = null;
    private ?string $dimensiones = null;
    private ?string $materialPeligroso = null;
    private ?string $cveMaterialPeligroso = null;
    private ?string $embalaje = null;
    private ?string $descripEmbalaje = null;
    private ?string $valorMercancia = null;
    private ?string $moneda = null;
    private ?string $fraccionArancelaria = null;
    private ?string $uuidComercioExt = null;
    private ?string $tipoMateria = null;
    private ?string $descripcionMateria = null;
    private ?string $noIdentificacion = null;
    private ?string $claveSTCC = null;
    private ?string $sectorCOFEPRIS = null;
    private ?string $nombreIngredienteActivo = null;
    private ?string $nomQuimico = null;
    private ?string $denominacionGenericaProd = null;
    private ?string $denominacionDistintivaProd = null;
    private ?string $fabricante = null;
    private ?string $fechaCaducidad = null;
    private ?string $loteMedicamento = null;
    private ?string $formaFarmaceutica = null;
    private ?string $condicionesEspTransp = null;
    private ?string $registroSanitarioFolioAutorizacion = null;
    private ?string $permisoImportacion = null;
    private ?string $folioImpoVUCEM = null;
    private ?string $numCAS = null;
    private ?string $razonSocialEmpImp = null;
    private ?string $numRegSanPlagCOFEPRIS = null;
    private ?string $datosFabricante = null;
    private ?string $datosFormulador = null;
    private ?string $datosMaquilador = null;
    private ?string $usoAutorizado = null;
    private ?DetalleMercancia $detalleMercancia = null;
    private array $documentacionAduanera = [];
    private array $guiasIdentificacion = [];
    private array $cantidadTransporta = [];
    
    public function getBienesTransp(): string
    {
        return $this->bienesTransp;
    }
    
    public function setBienesTransp(string $bienesTransp): self
    {
        $this->bienesTransp = $bienesTransp;
        return $this;
    }
    
    public function getDescripcion(): string
    {
        return $this->descripcion;
    }
    
    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;
        return $this;
    }
    
    public function getCantidad(): string
    {
        return $this->cantidad;
    }
    
    public function setCantidad(string $cantidad): self
    {
        $this->cantidad = $cantidad;
        return $this;
    }
    
    public function getClaveUnidad(): string
    {
        return $this->claveUnidad;
    }
    
    public function setClaveUnidad(string $claveUnidad): self
    {
        $this->claveUnidad = $claveUnidad;
        return $this;
    }
    
    public function getClaveUnidadPeso(): string
    {
        return $this->claveUnidad;
    }
    
    public function setClaveUnidadPeso(string $claveUnidadPeso): self
    {
        $this->claveUnidad = $claveUnidadPeso;
        return $this;
    }
    
    public function getPesoEnKg(): string
    {
        return $this->pesoEnKg;
    }
    
    public function setPesoEnKg(string $pesoEnKg): self
    {
        $this->pesoEnKg = $pesoEnKg;
        return $this;
    }
    
    public function getUnidad(): ?string
    {
        return $this->unidad;
    }
    
    public function setUnidad(?string $unidad): self
    {
        $this->unidad = $unidad;
        return $this;
    }
    
    public function getDimensiones(): ?string
    {
        return $this->dimensiones;
    }
    
    public function setDimensiones(?string $dimensiones): self
    {
        $this->dimensiones = $dimensiones;
        return $this;
    }
    
    public function getMaterialPeligroso(): ?string
    {
        return $this->materialPeligroso;
    }
    
    public function setMaterialPeligroso(?string $materialPeligroso): self
    {
        $this->materialPeligroso = $materialPeligroso;
        return $this;
    }
    
    public function getCveMaterialPeligroso(): ?string
    {
        return $this->cveMaterialPeligroso;
    }
    
    public function setCveMaterialPeligroso(?string $cveMaterialPeligroso): self
    {
        $this->cveMaterialPeligroso = $cveMaterialPeligroso;
        return $this;
    }
    
    public function getEmbalaje(): ?string
    {
        return $this->embalaje;
    }
    
    public function setEmbalaje(?string $embalaje): self
    {
        $this->embalaje = $embalaje;
        return $this;
    }
    
    public function getDescripEmbalaje(): ?string
    {
        return $this->descripEmbalaje;
    }
    
    public function setDescripEmbalaje(?string $descripEmbalaje): self
    {
        $this->descripEmbalaje = $descripEmbalaje;
        return $this;
    }
    
    public function getValorMercancia(): ?string
    {
        return $this->valorMercancia;
    }
    
    public function setValorMercancia(?string $valorMercancia): self
    {
        $this->valorMercancia = $valorMercancia;
        return $this;
    }
    
    public function getMoneda(): ?string
    {
        return $this->moneda;
    }
    
    public function setMoneda(?string $moneda): self
    {
        $this->moneda = $moneda;
        return $this;
    }
    
    public function getFraccionArancelaria(): ?string
    {
        return $this->fraccionArancelaria;
    }
    
    public function setFraccionArancelaria(?string $fraccionArancelaria): self
    {
        $this->fraccionArancelaria = $fraccionArancelaria;
        return $this;
    }
    
    public function getUuidComercioExt(): ?string
    {
        return $this->uuidComercioExt;
    }
    
    public function setUuidComercioExt(?string $uuidComercioExt): self
    {
        $this->uuidComercioExt = $uuidComercioExt;
        return $this;
    }
    
    public function getTipoMateria(): ?string
    {
        return $this->tipoMateria;
    }
    
    public function setTipoMateria(?string $tipoMateria): self
    {
        $this->tipoMateria = $tipoMateria;
        return $this;
    }
    
    public function getDescripcionMateria(): ?string
    {
        return $this->descripcionMateria;
    }
    
    public function setDescripcionMateria(?string $descripcionMateria): self
    {
        $this->descripcionMateria = $descripcionMateria;
        return $this;
    }
    
    public function getNoIdentificacion(): ?string
    {
        return $this->noIdentificacion;
    }
    
    public function setNoIdentificacion(?string $noIdentificacion): self
    {
        $this->noIdentificacion = $noIdentificacion;
        return $this;
    }
    
    public function getClaveSTCC(): ?string
    {
        return $this->claveSTCC;
    }
    
    public function setClaveSTCC(?string $claveSTCC): self
    {
        $this->claveSTCC = $claveSTCC;
        return $this;
    }
    
    public function getSectorCOFEPRIS(): ?string
    {
        return $this->sectorCOFEPRIS;
    }
    
    public function setSectorCOFEPRIS(?string $sectorCOFEPRIS): self
    {
        $this->sectorCOFEPRIS = $sectorCOFEPRIS;
        return $this;
    }
    
    public function getNombreIngredienteActivo(): ?string
    {
        return $this->nombreIngredienteActivo;
    }
    
    public function setNombreIngredienteActivo(?string $nombreIngredienteActivo): self
    {
        $this->nombreIngredienteActivo = $nombreIngredienteActivo;
        return $this;
    }
    
    public function getNomQuimico(): ?string
    {
        return $this->nomQuimico;
    }
    
    public function setNomQuimico(?string $nomQuimico): self
    {
        $this->nomQuimico = $nomQuimico;
        return $this;
    }
    
    public function getDenominacionGenericaProd(): ?string
    {
        return $this->denominacionGenericaProd;
    }
    
    public function setDenominacionGenericaProd(?string $denominacionGenericaProd): self
    {
        $this->denominacionGenericaProd = $denominacionGenericaProd;
        return $this;
    }
    
    public function getDenominacionDistintivaProd(): ?string
    {
        return $this->denominacionDistintivaProd;
    }
    
    public function setDenominacionDistintivaProd(?string $denominacionDistintivaProd): self
    {
        $this->denominacionDistintivaProd = $denominacionDistintivaProd;
        return $this;
    }
    
    public function getFabricante(): ?string
    {
        return $this->fabricante;
    }
    
    public function setFabricante(?string $fabricante): self
    {
        $this->fabricante = $fabricante;
        return $this;
    }
    
    public function getFechaCaducidad(): ?string
    {
        return $this->fechaCaducidad;
    }
    
    public function setFechaCaducidad(?string $fechaCaducidad): self
    {
        $this->fechaCaducidad = $fechaCaducidad;
        return $this;
    }
    
    public function getLoteMedicamento(): ?string
    {
        return $this->loteMedicamento;
    }
    
    public function setLoteMedicamento(?string $loteMedicamento): self
    {
        $this->loteMedicamento = $loteMedicamento;
        return $this;
    }
    
    public function getFormaFarmaceutica(): ?string
    {
        return $this->formaFarmaceutica;
    }
    
    public function setFormaFarmaceutica(?string $formaFarmaceutica): self
    {
        $this->formaFarmaceutica = $formaFarmaceutica;
        return $this;
    }
    
    public function getCondicionesEspTransp(): ?string
    {
        return $this->condicionesEspTransp;
    }
    
    public function setCondicionesEspTransp(?string $condicionesEspTransp): self
    {
        $this->condicionesEspTransp = $condicionesEspTransp;
        return $this;
    }
    
    public function getRegistroSanitarioFolioAutorizacion(): ?string
    {
        return $this->registroSanitarioFolioAutorizacion;
    }
    
    public function setRegistroSanitarioFolioAutorizacion(?string $registroSanitarioFolioAutorizacion): self
    {
        $this->registroSanitarioFolioAutorizacion = $registroSanitarioFolioAutorizacion;
        return $this;
    }
    
    public function getPermisoImportacion(): ?string
    {
        return $this->permisoImportacion;
    }
    
    public function setPermisoImportacion(?string $permisoImportacion): self
    {
        $this->permisoImportacion = $permisoImportacion;
        return $this;
    }
    
    public function getFolioImpoVUCEM(): ?string
    {
        return $this->folioImpoVUCEM;
    }
    
    public function setFolioImpoVUCEM(?string $folioImpoVUCEM): self
    {
        $this->folioImpoVUCEM = $folioImpoVUCEM;
        return $this;
    }
    
    public function getNumCAS(): ?string
    {
        return $this->numCAS;
    }
    
    public function setNumCAS(?string $numCAS): self
    {
        $this->numCAS = $numCAS;
        return $this;
    }
    
    public function getRazonSocialEmpImp(): ?string
    {
        return $this->razonSocialEmpImp;
    }
    
    public function setRazonSocialEmpImp(?string $razonSocialEmpImp): self
    {
        $this->razonSocialEmpImp = $razonSocialEmpImp;
        return $this;
    }
    
    public function getNumRegSanPlagCOFEPRIS(): ?string
    {
        return $this->numRegSanPlagCOFEPRIS;
    }
    
    public function setNumRegSanPlagCOFEPRIS(?string $numRegSanPlagCOFEPRIS): self
    {
        $this->numRegSanPlagCOFEPRIS = $numRegSanPlagCOFEPRIS;
        return $this;
    }
    
    public function getDatosFabricante(): ?string
    {
        return $this->datosFabricante;
    }
    
    public function setDatosFabricante(?string $datosFabricante): self
    {
        $this->datosFabricante = $datosFabricante;
        return $this;
    }
    
    public function getDatosFormulador(): ?string
    {
        return $this->datosFormulador;
    }
    
    public function setDatosFormulador(?string $datosFormulador): self
    {
        $this->datosFormulador = $datosFormulador;
        return $this;
    }
    
    public function getDatosMaquilador(): ?string
    {
        return $this->datosMaquilador;
    }
    
    public function setDatosMaquilador(?string $datosMaquilador): self
    {
        $this->datosMaquilador = $datosMaquilador;
        return $this;
    }
    
    public function getUsoAutorizado(): ?string
    {
        return $this->usoAutorizado;
    }
    
    public function setUsoAutorizado(?string $usoAutorizado): self
    {
        $this->usoAutorizado = $usoAutorizado;
        return $this;
    }
    
    public function getDetalleMercancia(): ?DetalleMercancia
    {
        return $this->detalleMercancia;
    }
    
    public function setDetalleMercancia(?DetalleMercancia $detalleMercancia): self
    {
        $this->detalleMercancia = $detalleMercancia;
        return $this;
    }
    
    public function getDocumentacionAduanera(): array
    {
        return $this->documentacionAduanera;
    }
    
    public function addDocumentacionAduanera(array $documentacionAduanera): self
    {
        $this->documentacionAduanera[] = $documentacionAduanera;
        return $this;
    }
    
    public function getGuiasIdentificacion(): array
    {
        return $this->guiasIdentificacion;
    }
    
    public function addGuiaIdentificacion(array $guiaIdentificacion): self
    {
        $this->guiasIdentificacion[] = $guiaIdentificacion;
        return $this;
    }
    
    public function getCantidadTransporta(): array
    {
        return $this->cantidadTransporta;
    }
    
    public function addCantidadTransporta(array $cantidadTransporta): self
    {
        $this->cantidadTransporta[] = $cantidadTransporta;
        return $this;
    }
}
