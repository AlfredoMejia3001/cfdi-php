<?php

class CartaPorte31
{
    // Atributos del CFDI principal (como CFDI40.php)
    private $cComprobante;
    private $cEmisor;
    private $cReceptor;
    private $cConceptos = [];
    private $cImpuestos;
    private $cImpuestosTotales;
    
    // Atributos específicos de Carta Porte
    private $cCartaPorte;
    private $cRegimenesAduaneros;
    private $cUbicaciones;
    private $cMercancias;
    private $aUbicaciones = [];
    private $aMercancias = [];
    private $aRegimenesAduaneros = [];
    private $currentUbicacion = null;
    private $currentMercancia = null;

    public function __construct()
    {
        // Inicializar objetos del CFDI principal
        $this->cComprobante = new stdClass();
        $this->cEmisor = new stdClass();
        $this->cReceptor = new stdClass();
        $this->cImpuestos = new stdClass();
        $this->cImpuestosTotales = new stdClass();
        
        // Inicializar objetos de Carta Porte
        $this->cCartaPorte = new stdClass();
        $this->cRegimenesAduaneros = new stdClass();
        $this->cUbicaciones = new stdClass();
        $this->cMercancias = new stdClass();
    }

    public function Schemma()
    {
        return array(
            "CFDI40" => "http://www.sat.gob.mx/esquemas/cfd/4 http://www.sat.gob.mx/esquemas/cfd/4/cfdv40.xsd",
            "CartaPorte31" => "http://www.sat.gob.mx/CartaPorte31 http://www.sat.gob.mx/sitio_internet/cfd/CartaPorte/CartaPorte31.xsd"
        );
    }

            // Métodos del CFDI principal (como CFDI40.php)
    public function CFDI40($NoCertificado, $Serie, $Folio, $Fecha, $SubTotal, $Moneda, $Total, $TipoDeComprobante, $Exportacion, $MetodoPago, $FormaPago, $LugarExpedicion, $CondicionesDePago = null, $Descuento = null, $TipoCambio = null, $Confirmacion = null)
    {
        $this->cComprobante->Version = "4.0";
        $this->cComprobante->SchemaLocalization = $this->Schemma()["CFDI40"];
        $this->cComprobante->Xmlns = "http://www.sat.gob.mx/cfd/4";
        $this->cComprobante->XmlnsXsi = "http://www.w3.org/2001/XMLSchema-instance";
        $this->cComprobante->Sello = "";
        $this->cComprobante->NoCertificado = $NoCertificado;
        $this->cComprobante->Certificado = "";
        
        if (!empty(trim($Serie))) {
            $this->cComprobante->Serie = $Serie;
        }
        if (!empty(trim($Folio))) {
            $this->cComprobante->Folio = $Folio;
        }
        $this->cComprobante->Fecha = $Fecha ?? date('Y-m-d\TH:i:s');
        if (!empty(trim($CondicionesDePago))) {
            $this->cComprobante->CondicionesDePago = $CondicionesDePago;
        }
        $this->cComprobante->SubTotal = $SubTotal;
        $this->cComprobante->Moneda = $Moneda;
        $this->cComprobante->Total = $Total;
        if (!empty(trim($Descuento))) {
            $this->cComprobante->Descuento = $Descuento;
        }
        if (!empty(trim($TipoCambio))) {
            $this->cComprobante->TipoCambio = $TipoCambio;
        }
        $this->cComprobante->TipoDeComprobante = $TipoDeComprobante;
        $this->cComprobante->Exportacion = $Exportacion;
        if (!empty(trim($MetodoPago))) {
            $this->cComprobante->MetodoPago = $MetodoPago;
        }
        if (!empty(trim($FormaPago))) {
            $this->cComprobante->FormaPago = $FormaPago;
        }
        $this->cComprobante->LugarExpedicion = $LugarExpedicion;
        if (!empty(trim($Confirmacion))) {
            $this->cComprobante->Confirmacion = $Confirmacion;
        }
    }

    public function AgregarEmisor($Rfc, $NombreE, $RegimenFiscalE, $FacAtrAdquiriente = null)
    {
        $this->cEmisor->Rfc = $Rfc;
        $this->cEmisor->NombreE = $NombreE;
        $this->cEmisor->RegimenFiscalE = $RegimenFiscalE;
        if (!empty($FacAtrAdquiriente)) {
            $this->cEmisor->FacAtrAdquiriente = $FacAtrAdquiriente;
        }
    }

    public function AgregarReceptor($RfcR, $NombreR, $RegimenFiscalReceptor, $UsoCFDI, $DomicilioFiscalReceptor, $ResidenciaFiscal = null, $NumRegIdTrib = null)
    {
        $this->cReceptor->RfcR = $RfcR;
        $this->cReceptor->NombreR = $NombreR;
        $this->cReceptor->RegimenFiscalReceptor = $RegimenFiscalReceptor;
        $this->cReceptor->UsoCFDI = $UsoCFDI;
        $this->cReceptor->DomicilioFiscalReceptor = $DomicilioFiscalReceptor;
        if (!empty($ResidenciaFiscal)) {
            $this->cReceptor->ResidenciaFiscal = $ResidenciaFiscal;
        }
        if (!empty($NumRegIdTrib)) {
            $this->cReceptor->NumRegIdTrib = $NumRegIdTrib;
        }
    }

    public function AgregarConcepto($ClaveProdServ, $NoIdentificacion, $Cantidad, $ClaveUnidad, $Unidad = null, $Descripcion, $ValorUnitario, $Importe, $ObjetoImp, $Descuento = null)
    {
        $concepto = new stdClass();
        $concepto->ClaveProdServ = $ClaveProdServ;
        if (!empty($NoIdentificacion)) {
            $concepto->NoIdentificacion = $NoIdentificacion;
        }
        $concepto->Cantidad = $Cantidad;
        $concepto->ClaveUnidad = $ClaveUnidad;
        if (!empty($Unidad)) {
            $concepto->Unidad = $Unidad;
        }
        $concepto->Descripcion = $Descripcion;
        $concepto->ValorUnitario = $ValorUnitario;
        $concepto->Importe = $Importe;
        if (!empty($Descuento)) {
            $concepto->Descuento = $Descuento;
        }
        $concepto->ObjetoImp = $ObjetoImp;
        
        $this->cConceptos[] = $concepto;
    }

    public function AgregarImpuestosTotales($TotalImpuestosRetenidos, $TotalImpuestosTrasladados)
    {
        $this->cImpuestosTotales = new stdClass();
        $this->cImpuestosTotales->TotalImpuestosRetenidos = $TotalImpuestosRetenidos;
        $this->cImpuestosTotales->TotalImpuestosTrasladados = $TotalImpuestosTrasladados;
        
        // Initialize Retenciones and Traslados arrays for total-level taxes
        $this->cImpuestosTotales->Retenciones = [];
        $this->cImpuestosTotales->Traslados = [];
    }
    
    public function AgregarRetencionTotal($Impuesto, $Importe)
    {
        if (!isset($this->cImpuestosTotales->Retenciones)) {
            $this->cImpuestosTotales->Retenciones = [];
        }
        
        $retencion = new stdClass();
        $retencion->Impuesto = $Impuesto;
        $retencion->Importe = $Importe;
        
        $this->cImpuestosTotales->Retenciones[] = $retencion;
    }
    
    public function AgregarTrasladoTotal($Base, $Importe, $Impuesto, $TasaOCuota, $TipoFactor)
    {
        if (!isset($this->cImpuestosTotales->Traslados)) {
            $this->cImpuestosTotales->Traslados = [];
        }
        
        $traslado = new stdClass();
        $traslado->Base = $Base;
        $traslado->Impuesto = $Impuesto;
        $traslado->TipoFactor = $TipoFactor;
        $traslado->TasaOCuota = $TasaOCuota;
        $traslado->Importe = $Importe;
        
        $this->cImpuestosTotales->Traslados[] = $traslado;
    }

    // Métodos específicos de Carta Porte
    public function CartaPorte31($TranspInternac = null, $EntradaSalidaMerc = null, $ViaEntradaSalida = null, $TotalDistRec = null, $RegistroISTMO = null, $UbicacionPoloOrigen = null, $UbicacionPoloDestino = null, $IdCCP = null, $PaisOrigenDestino = null)
    {
        $this->cCartaPorte->Version = "3.1";
        $this->cCartaPorte->SchemaLocalization = $this->Schemma()["CartaPorte31"];
        $this->cCartaPorte->Xmlns = "http://www.sat.gob.mx/CartaPorte31";
        $this->cCartaPorte->XmlnsXsi = "http://www.w3.org/2001/XMLSchema-instance";
        
        if (!empty(trim($TranspInternac))) {
            $this->cCartaPorte->TranspInternac = $TranspInternac;
        }
        if (!empty(trim($EntradaSalidaMerc))) {
            $this->cCartaPorte->EntradaSalidaMerc = $EntradaSalidaMerc;
        }
        if (!empty(trim($ViaEntradaSalida))) {
            $this->cCartaPorte->ViaEntradaSalida = $ViaEntradaSalida;
        }
        if (!empty(trim($TotalDistRec))) {
            $this->cCartaPorte->TotalDistRec = $TotalDistRec;
        }
        if (!empty(trim($RegistroISTMO))) {
            $this->cCartaPorte->RegistroISTMO = $RegistroISTMO;
        }
        if (!empty(trim($UbicacionPoloOrigen))) {
            $this->cCartaPorte->UbicacionPoloOrigen = $UbicacionPoloOrigen;
        }
        if (!empty(trim($UbicacionPoloDestino))) {
            $this->cCartaPorte->UbicacionPoloDestino = $UbicacionPoloDestino;
        }
        if (!empty(trim($IdCCP))) {
            $this->cCartaPorte->IdCCP = $IdCCP;
        }
        if (!empty(trim($PaisOrigenDestino))) {
            $this->cCartaPorte->PaisOrigenDestino = $PaisOrigenDestino;
        }
    }

    public function AgregarRegimenAduanero($RegimenAduanero)
    {
        $regimen = new stdClass();
        $regimen->RegimenAduanero = $RegimenAduanero;
        $this->aRegimenesAduaneros[] = $regimen;
    }

    public function AgregarRegimenesAduaneros()
    {
        if (!empty($this->aRegimenesAduaneros)) {
            $this->cRegimenesAduaneros->RegimenAduaneroCCP = $this->aRegimenesAduaneros;
        }
    }

    public function AgregarUbicacion($TipoUbicacion, $RFCRemitenteDestinatario, $FechaHoraSalidaLlegada, $IDUbicacion = null, $NombreRemitenteDestinatario = null, $NumRegIdTrib = null, $ResidenciaFiscal = null, $NumEstacion = null, $NombreEstacion = null, $NavegacionTrafico = null, $TipoEstacion = null, $DistanciaRecorrida = null)
    {
        $ubicacion = new stdClass();
        $ubicacion->TipoUbicacion = $TipoUbicacion;
        $ubicacion->RFCRemitenteDestinatario = $RFCRemitenteDestinatario;
        $ubicacion->FechaHoraSalidaLlegada = $FechaHoraSalidaLlegada;
        
        if (!empty(trim($IDUbicacion))) {
            $ubicacion->IDUbicacion = $IDUbicacion;
        }
        if (!empty(trim($NombreRemitenteDestinatario))) {
            $ubicacion->NombreRemitenteDestinatario = $NombreRemitenteDestinatario;
        }
        if (!empty(trim($NumRegIdTrib))) {
            $ubicacion->NumRegIdTrib = $NumRegIdTrib;
        }
        if (!empty(trim($ResidenciaFiscal))) {
            $ubicacion->ResidenciaFiscal = $ResidenciaFiscal;
        }
        if (!empty(trim($NumEstacion))) {
            $ubicacion->NumEstacion = $NumEstacion;
        }
        if (!empty(trim($NombreEstacion))) {
            $ubicacion->NombreEstacion = $NombreEstacion;
        }
        if (!empty(trim($NavegacionTrafico))) {
            $ubicacion->NavegacionTrafico = $NavegacionTrafico;
        }
        if (!empty(trim($TipoEstacion))) {
            $ubicacion->TipoEstacion = $TipoEstacion;
        }
        if (!empty(trim($DistanciaRecorrida))) {
            $ubicacion->DistanciaRecorrida = $DistanciaRecorrida;
        }
        
        $this->aUbicaciones[] = $ubicacion;
    }

    public function AgregarDomicilio($Calle = null, $NumeroExterior = null, $NumeroInterior = null, $Colonia = null, $Localidad = null, $Referencia = null, $Municipio = null, $Estado, $Pais, $CodigoPostal)
    {
        // Buscar la última ubicación agregada
        if (empty($this->aUbicaciones)) {
            throw new Exception("Debe agregar una ubicación antes de agregar un domicilio");
        }
        
        $ultimaUbicacion = end($this->aUbicaciones);
        
        $domicilio = new stdClass();
        $domicilio->Estado = $Estado;
        $domicilio->Pais = $Pais;
        $domicilio->CodigoPostal = $CodigoPostal;
        
        if (!empty(trim($Calle))) {
            $domicilio->Calle = $Calle;
        }
        if (!empty(trim($NumeroExterior))) {
            $domicilio->NumeroExterior = $NumeroExterior;
        }
        if (!empty(trim($NumeroInterior))) {
            $domicilio->NumeroInterior = $NumeroInterior;
        }
        if (!empty(trim($Colonia))) {
            $domicilio->Colonia = $Colonia;
        }
        if (!empty(trim($Localidad))) {
            $domicilio->Localidad = $Localidad;
        }
        if (!empty(trim($Referencia))) {
            $domicilio->Referencia = $Referencia;
        }
        if (!empty(trim($Municipio))) {
            $domicilio->Municipio = $Municipio;
        }
        
        $ultimaUbicacion->Domicilio = $domicilio;
    }

    public function AgregarMercancia($BienesTransp, $Descripcion, $Cantidad, $ClaveUnidad, $Unidad = null, $Dimensiones = null, $MaterialPeligroso = null, $CveMaterialPeligroso = null, $Embalaje = null, $DescripEmbalaje = null, $PesoEnKg, $ValorMercancia = null, $Moneda = null, $FraccionArancelaria = null, $UUIDComercioExt = null, $TipoMateria = null, $DescripcionMateria = null, $NoIdentificacion = null, $ClaveSTCC = null, $SectorCOFEPRIS = null, $NombreIngredienteActivo = null, $NomQuimico = null, $DenominacionGenericaProd = null, $DenominacionDistintivaProd = null, $Fabricante = null, $FechaCaducidad = null, $LoteMedicamento = null, $FormaFarmaceutica = null, $CondicionesEspTransp = null, $RegistroSanitarioFolioAutorizacion = null, $PermisoImportacion = null, $FolioImpoVUCEM = null, $NumCAS = null, $RazonSocialEmpImp = null, $NumRegSanPlagCOFEPRIS = null, $DatosFabricante = null, $DatosFormulador = null, $DatosMaquilador = null, $UsoAutorizado = null)
    {
        $mercancia = new stdClass();
        $mercancia->BienesTransp = $BienesTransp;
        $mercancia->Descripcion = $Descripcion;
        $mercancia->Cantidad = $Cantidad;
        $mercancia->ClaveUnidad = $ClaveUnidad;
        $mercancia->PesoEnKg = $PesoEnKg;
        
        if (!empty(trim($Unidad))) {
            $mercancia->Unidad = $Unidad;
        }
        if (!empty(trim($Dimensiones))) {
            $mercancia->Dimensiones = $Dimensiones;
        }
        if (!empty(trim($MaterialPeligroso))) {
            $mercancia->MaterialPeligroso = $MaterialPeligroso;
        }
        if (!empty(trim($CveMaterialPeligroso))) {
            $mercancia->CveMaterialPeligroso = $CveMaterialPeligroso;
        }
        if (!empty(trim($Embalaje))) {
            $mercancia->Embalaje = $Embalaje;
        }
        if (!empty(trim($DescripEmbalaje))) {
            $mercancia->DescripEmbalaje = $DescripEmbalaje;
        }
        if (!empty(trim($ValorMercancia))) {
            $mercancia->ValorMercancia = $ValorMercancia;
        }
        if (!empty(trim($Moneda))) {
            $mercancia->Moneda = $Moneda;
        }
        if (!empty(trim($FraccionArancelaria))) {
            $mercancia->FraccionArancelaria = $FraccionArancelaria;
        }
        if (!empty(trim($UUIDComercioExt))) {
            $mercancia->UUIDComercioExt = $UUIDComercioExt;
        }
        if (!empty(trim($TipoMateria))) {
            $mercancia->TipoMateria = $TipoMateria;
        }
        if (!empty(trim($DescripcionMateria))) {
            $mercancia->DescripcionMateria = $DescripcionMateria;
        }
        if (!empty(trim($NoIdentificacion))) {
            $mercancia->NoIdentificacion = $NoIdentificacion;
        }
        if (!empty(trim($ClaveSTCC))) {
            $mercancia->ClaveSTCC = $ClaveSTCC;
        }
        if (!empty(trim($SectorCOFEPRIS))) {
            $mercancia->SectorCOFEPRIS = $SectorCOFEPRIS;
        }
        if (!empty(trim($NombreIngredienteActivo))) {
            $mercancia->NombreIngredienteActivo = $NombreIngredienteActivo;
        }
        if (!empty(trim($NomQuimico))) {
            $mercancia->NomQuimico = $NomQuimico;
        }
        if (!empty(trim($DenominacionGenericaProd))) {
            $mercancia->DenominacionGenericaProd = $DenominacionGenericaProd;
        }
        if (!empty(trim($DenominacionDistintivaProd))) {
            $mercancia->DenominacionDistintivaProd = $DenominacionDistintivaProd;
        }
        if (!empty(trim($Fabricante))) {
            $mercancia->Fabricante = $Fabricante;
        }
        if (!empty(trim($FechaCaducidad))) {
            $mercancia->FechaCaducidad = $FechaCaducidad;
        }
        if (!empty(trim($LoteMedicamento))) {
            $mercancia->LoteMedicamento = $LoteMedicamento;
        }
        if (!empty(trim($FormaFarmaceutica))) {
            $mercancia->FormaFarmaceutica = $FormaFarmaceutica;
        }
        if (!empty(trim($CondicionesEspTransp))) {
            $mercancia->CondicionesEspTransp = $CondicionesEspTransp;
        }
        if (!empty(trim($RegistroSanitarioFolioAutorizacion))) {
            $mercancia->RegistroSanitarioFolioAutorizacion = $RegistroSanitarioFolioAutorizacion;
        }
        if (!empty(trim($PermisoImportacion))) {
            $mercancia->PermisoImportacion = $PermisoImportacion;
        }
        if (!empty(trim($FolioImpoVUCEM))) {
            $mercancia->FolioImpoVUCEM = $FolioImpoVUCEM;
        }
        if (!empty(trim($NumCAS))) {
            $mercancia->NumCAS = $NumCAS;
        }
        if (!empty(trim($RazonSocialEmpImp))) {
            $mercancia->RazonSocialEmpImp = $RazonSocialEmpImp;
        }
        if (!empty(trim($NumRegSanPlagCOFEPRIS))) {
            $mercancia->NumRegSanPlagCOFEPRIS = $NumRegSanPlagCOFEPRIS;
        }
        if (!empty(trim($DatosFabricante))) {
            $mercancia->DatosFabricante = $DatosFabricante;
        }
        if (!empty(trim($DatosFormulador))) {
            $mercancia->DatosFormulador = $DatosFormulador;
        }
        if (!empty(trim($DatosMaquilador))) {
            $mercancia->DatosMaquilador = $DatosMaquilador;
        }
        if (!empty(trim($UsoAutorizado))) {
            $mercancia->UsoAutorizado = $UsoAutorizado;
        }
        
        $this->aMercancias[] = $mercancia;
        $this->currentMercancia = $mercancia;
    }

    public function AgregarDetalleMercancia($UnidadPesoMerc, $PesoBruto, $PesoNeto, $PesoTara, $NumPiezas = null)
    {
        if ($this->currentMercancia === null) {
            throw new Exception("Debe agregar una mercancía antes de agregar su detalle");
        }
        
        $detalle = new stdClass();
        $detalle->UnidadPesoMerc = $UnidadPesoMerc;
        $detalle->PesoBruto = $PesoBruto;
        $detalle->PesoNeto = $PesoNeto;
        $detalle->PesoTara = $PesoTara;
        
        if (!empty(trim($NumPiezas))) {
            $detalle->NumPiezas = $NumPiezas;
        }
        
        $this->currentMercancia->DetalleMercancia = $detalle;
    }

    public function AgregarDocumentacionAduanera($TipoDocumento, $NumPedimento = null, $IdentDocAduanero = null, $RFCImpo = null)
    {
        if ($this->currentMercancia === null) {
            throw new Exception("Debe agregar una mercancía antes de agregar documentación aduanera");
        }
        
        $docAduanera = new stdClass();
        $docAduanera->TipoDocumento = $TipoDocumento;
        
        if (!empty(trim($NumPedimento))) {
            $docAduanera->NumPedimento = $NumPedimento;
        }
        if (!empty(trim($IdentDocAduanero))) {
            $docAduanera->IdentDocAduanero = $IdentDocAduanero;
        }
        if (!empty(trim($RFCImpo))) {
            $docAduanera->RFCImpo = $RFCImpo;
        }
        
        if (!isset($this->currentMercancia->DocumentacionAduanera)) {
            $this->currentMercancia->DocumentacionAduanera = [];
        }
        $this->currentMercancia->DocumentacionAduanera[] = $docAduanera;
    }

    public function AgregarGuiaIdentificacion($NumeroGuiaIdentificacion, $DescripGuiaIdentificacion, $PesoGuiaIdentificacion)
    {
        if ($this->currentMercancia === null) {
            throw new Exception("Debe agregar una mercancía antes de agregar guía de identificación");
        }
        
        $guia = new stdClass();
        $guia->NumeroGuiaIdentificacion = $NumeroGuiaIdentificacion;
        $guia->DescripGuiaIdentificacion = $DescripGuiaIdentificacion;
        $guia->PesoGuiaIdentificacion = $PesoGuiaIdentificacion;
        
        if (!isset($this->currentMercancia->GuiasIdentificacion)) {
            $this->currentMercancia->GuiasIdentificacion = [];
        }
        $this->currentMercancia->GuiasIdentificacion[] = $guia;
    }

    public function AgregarCantidadTransporta($Cantidad, $IDOrigen, $IDDestino, $CvesTransporte = null)
    {
        if ($this->currentMercancia === null) {
            throw new Exception("Debe agregar una mercancía antes de agregar cantidad transporta");
        }
        
        $cantidadTransporta = new stdClass();
        $cantidadTransporta->Cantidad = $Cantidad;
        $cantidadTransporta->IDOrigen = $IDOrigen;
        $cantidadTransporta->IDDestino = $IDDestino;
        
        if (!empty(trim($CvesTransporte))) {
            $cantidadTransporta->CvesTransporte = $CvesTransporte;
        }
        
        if (!isset($this->currentMercancia->CantidadTransporta)) {
            $this->currentMercancia->CantidadTransporta = [];
        }
        $this->currentMercancia->CantidadTransporta[] = $cantidadTransporta;
    }

    public function AgregarAutotransporte($PermSCT, $NumPermisoSCT, $ConfigVehicular, $PesoBrutoVehicular, $PlacaVM, $AnioModeloVM, $AseguraRespCivil, $PolizaRespCivil, $AseguraMedAmbiente = null, $PolizaMedAmbiente = null, $AseguraCarga = null, $PolizaCarga = null, $PrimaSeguro = null)
    {
        $autotransporte = new stdClass();
        $autotransporte->PermSCT = $PermSCT;
        $autotransporte->NumPermisoSCT = $NumPermisoSCT;
        
        // Identificación Vehicular
        $identificacion = new stdClass();
        $identificacion->ConfigVehicular = $ConfigVehicular;
        $identificacion->PesoBrutoVehicular = $PesoBrutoVehicular;
        $identificacion->PlacaVM = $PlacaVM;
        $identificacion->AnioModeloVM = $AnioModeloVM;
        $autotransporte->IdentificacionVehicular = $identificacion;
        
        // Seguros
        $seguros = new stdClass();
        $seguros->AseguraRespCivil = $AseguraRespCivil;
        $seguros->PolizaRespCivil = $PolizaRespCivil;
        
        if (!empty(trim($AseguraMedAmbiente))) {
            $seguros->AseguraMedAmbiente = $AseguraMedAmbiente;
        }
        if (!empty(trim($PolizaMedAmbiente))) {
            $seguros->PolizaMedAmbiente = $PolizaMedAmbiente;
        }
        if (!empty(trim($AseguraCarga))) {
            $seguros->AseguraCarga = $AseguraCarga;
        }
        if (!empty(trim($PolizaCarga))) {
            $seguros->PolizaCarga = $PolizaCarga;
        }
        if (!empty(trim($PrimaSeguro))) {
            $seguros->PrimaSeguro = $PrimaSeguro;
        }
        
        $autotransporte->Seguros = $seguros;
        
        $this->cMercancias->Autotransporte = $autotransporte;
    }

    public function AgregarRemolque($SubTipoRem, $Placa)
    {
        if (!isset($this->cMercancias->Autotransporte)) {
            throw new Exception("Debe agregar autotransporte antes de agregar remolque");
        }
        
        $remolque = new stdClass();
        $remolque->SubTipoRem = $SubTipoRem;
        $remolque->Placa = $Placa;
        
        if (!isset($this->cMercancias->Autotransporte->Remolques)) {
            $this->cMercancias->Autotransporte->Remolques = new stdClass();
            $this->cMercancias->Autotransporte->Remolques->Remolque = [];
        }
        
        $this->cMercancias->Autotransporte->Remolques->Remolque[] = $remolque;
    }

    public function AgregarTransporteMaritimo($TipoEmbarcacion, $Matricula, $NumeroOMI, $NacionalidadEmbarc, $UnidadesDeArqBruto, $TipoCarga, $PermSCT = null, $NumPermisoSCT = null, $NombreAseg = null, $NumPolizaSeguro = null, $AnioEmbarcacion = null, $NombreEmbarc = null, $Eslora = null, $Manga = null, $Calado = null, $Puntal = null, $LineaNaviera = null, $NombreAgenteNaviero, $NumAutorizacionNaviero, $NumViaje = null, $NumConocEmbarc = null, $PermisoTempNavegacion = null)
    {
        $transporteMaritimo = new stdClass();
        $transporteMaritimo->TipoEmbarcacion = $TipoEmbarcacion;
        $transporteMaritimo->Matricula = $Matricula;
        $transporteMaritimo->NumeroOMI = $NumeroOMI;
        $transporteMaritimo->NacionalidadEmbarc = $NacionalidadEmbarc;
        $transporteMaritimo->UnidadesDeArqBruto = $UnidadesDeArqBruto;
        $transporteMaritimo->TipoCarga = $TipoCarga;
        $transporteMaritimo->NombreAgenteNaviero = $NombreAgenteNaviero;
        $transporteMaritimo->NumAutorizacionNaviero = $NumAutorizacionNaviero;
        
        if (!empty(trim($PermSCT))) {
            $transporteMaritimo->PermSCT = $PermSCT;
        }
        if (!empty(trim($NumPermisoSCT))) {
            $transporteMaritimo->NumPermisoSCT = $NumPermisoSCT;
        }
        if (!empty(trim($NombreAseg))) {
            $transporteMaritimo->NombreAseg = $NombreAseg;
        }
        if (!empty(trim($NumPolizaSeguro))) {
            $transporteMaritimo->NumPolizaSeguro = $NumPolizaSeguro;
        }
        if (!empty(trim($AnioEmbarcacion))) {
            $transporteMaritimo->AnioEmbarcacion = $AnioEmbarcacion;
        }
        if (!empty(trim($NombreEmbarc))) {
            $transporteMaritimo->NombreEmbarc = $NombreEmbarc;
        }
        if (!empty(trim($Eslora))) {
            $transporteMaritimo->Eslora = $Eslora;
        }
        if (!empty(trim($Manga))) {
            $transporteMaritimo->Manga = $Manga;
        }
        if (!empty(trim($Calado))) {
            $transporteMaritimo->Calado = $Calado;
        }
        if (!empty(trim($Puntal))) {
            $transporteMaritimo->Puntal = $Puntal;
        }
        if (!empty(trim($LineaNaviera))) {
            $transporteMaritimo->LineaNaviera = $LineaNaviera;
        }
        if (!empty(trim($NumViaje))) {
            $transporteMaritimo->NumViaje = $NumViaje;
        }
        if (!empty(trim($NumConocEmbarc))) {
            $transporteMaritimo->NumConocEmbarc = $NumConocEmbarc;
        }
        if (!empty(trim($PermisoTempNavegacion))) {
            $transporteMaritimo->PermisoTempNavegacion = $PermisoTempNavegacion;
        }
        
        $this->cMercancias->TransporteMaritimo = $transporteMaritimo;
    }

    public function AgregarContenedor($TipoContenedor, $MatriculaContenedor = null, $NumPrecinto = null, $IdCCPRelacionado = null, $PlacaVMCCP = null, $FechaCertificacionCCP = null)
    {
        if (!isset($this->cMercancias->TransporteMaritimo)) {
            throw new Exception("Debe agregar transporte marítimo antes de agregar contenedor");
        }
        
        $contenedor = new stdClass();
        $contenedor->TipoContenedor = $TipoContenedor;
        
        if (!empty(trim($MatriculaContenedor))) {
            $contenedor->MatriculaContenedor = $MatriculaContenedor;
        }
        if (!empty(trim($NumPrecinto))) {
            $contenedor->NumPrecinto = $NumPrecinto;
        }
        if (!empty(trim($IdCCPRelacionado))) {
            $contenedor->IdCCPRelacionado = $IdCCPRelacionado;
        }
        if (!empty(trim($PlacaVMCCP))) {
            $contenedor->PlacaVMCCP = $PlacaVMCCP;
        }
        if (!empty(trim($FechaCertificacionCCP))) {
            $contenedor->FechaCertificacionCCP = $FechaCertificacionCCP;
        }
        
        if (!isset($this->cMercancias->TransporteMaritimo->Contenedor)) {
            $this->cMercancias->TransporteMaritimo->Contenedor = [];
        }
        
        $this->cMercancias->TransporteMaritimo->Contenedor[] = $contenedor;
    }

    public function AgregarTransporteAereo($PermSCT, $NumPermisoSCT, $NumeroGuia, $CodigoTransportista, $MatriculaAeronave = null, $NombreAseg = null, $NumPolizaSeguro = null, $LugarContrato = null, $RFCEmbarcador = null, $NumRegIdTribEmbarc = null, $ResidenciaFiscalEmbarc = null, $NombreEmbarcador = null)
    {
        $transporteAereo = new stdClass();
        $transporteAereo->PermSCT = $PermSCT;
        $transporteAereo->NumPermisoSCT = $NumPermisoSCT;
        $transporteAereo->NumeroGuia = $NumeroGuia;
        $transporteAereo->CodigoTransportista = $CodigoTransportista;
        
        if (!empty(trim($MatriculaAeronave))) {
            $transporteAereo->MatriculaAeronave = $MatriculaAeronave;
        }
        if (!empty(trim($NombreAseg))) {
            $transporteAereo->NombreAseg = $NombreAseg;
        }
        if (!empty(trim($NumPolizaSeguro))) {
            $transporteAereo->NumPolizaSeguro = $NumPolizaSeguro;
        }
        if (!empty(trim($LugarContrato))) {
            $transporteAereo->LugarContrato = $LugarContrato;
        }
        if (!empty(trim($RFCEmbarcador))) {
            $transporteAereo->RFCEmbarcador = $RFCEmbarcador;
        }
        if (!empty(trim($NumRegIdTribEmbarc))) {
            $transporteAereo->NumRegIdTribEmbarc = $NumRegIdTribEmbarc;
        }
        if (!empty(trim($ResidenciaFiscalEmbarc))) {
            $transporteAereo->ResidenciaFiscalEmbarc = $ResidenciaFiscalEmbarc;
        }
        if (!empty(trim($NombreEmbarcador))) {
            $transporteAereo->NombreEmbarcador = $NombreEmbarcador;
        }
        
        $this->cMercancias->TransporteAereo = $transporteAereo;
    }

    public function AgregarTransporteFerroviario($TipoDeServicio, $TipoDeTrafico, $NombreAseg = null, $NumPolizaSeguro = null)
    {
        $transporteFerroviario = new stdClass();
        $transporteFerroviario->TipoDeServicio = $TipoDeServicio;
        $transporteFerroviario->TipoDeTrafico = $TipoDeTrafico;
        
        if (!empty(trim($NombreAseg))) {
            $transporteFerroviario->NombreAseg = $NombreAseg;
        }
        if (!empty(trim($NumPolizaSeguro))) {
            $transporteFerroviario->NumPolizaSeguro = $NumPolizaSeguro;
        }
        
        $this->cMercancias->TransporteFerroviario = $transporteFerroviario;
    }

    public function AgregarDerechosDePaso($TipoDerechoDePaso, $KilometrajePagado)
    {
        if (!isset($this->cMercancias->TransporteFerroviario)) {
            throw new Exception("Debe agregar transporte ferroviario antes de agregar derechos de paso");
        }
        
        $derechosDePaso = new stdClass();
        $derechosDePaso->TipoDerechoDePaso = $TipoDerechoDePaso;
        $derechosDePaso->KilometrajePagado = $KilometrajePagado;
        
        if (!isset($this->cMercancias->TransporteFerroviario->DerechosDePaso)) {
            $this->cMercancias->TransporteFerroviario->DerechosDePaso = [];
        }
        
        $this->cMercancias->TransporteFerroviario->DerechosDePaso[] = $derechosDePaso;
    }

    public function AgregarCarro($TipoCarro, $MatriculaCarro, $GuiaCarro, $ToneladasNetasCarro)
    {
        if (!isset($this->cMercancias->TransporteFerroviario)) {
            throw new Exception("Debe agregar transporte ferroviario antes de agregar carro");
        }
        
        $carro = new stdClass();
        $carro->TipoCarro = $TipoCarro;
        $carro->MatriculaCarro = $MatriculaCarro;
        $carro->GuiaCarro = $GuiaCarro;
        $carro->ToneladasNetasCarro = $ToneladasNetasCarro;
        
        if (!isset($this->cMercancias->TransporteFerroviario->Carro)) {
            $this->cMercancias->TransporteFerroviario->Carro = [];
        }
        
        $this->cMercancias->TransporteFerroviario->Carro[] = $carro;
    }

    public function AgregarFiguraTransporte($TipoFigura, $RFCFigura, $NumLicencia, $NombreFigura, $Calle = null, $NumeroExterior = null, $NumeroInterior = null, $Colonia = null, $Localidad = null, $Referencia = null, $Municipio = null, $Estado = null, $Pais = null, $CodigoPostal = null, $NumRegIdTribFigura = null, $ResidenciaFiscalFigura = null)
    {
        $figuraTransporte = new stdClass();
        $figuraTransporte->TipoFigura = $TipoFigura;
        $figuraTransporte->RFCFigura = $RFCFigura;
        $figuraTransporte->NumLicencia = $NumLicencia;
        $figuraTransporte->NombreFigura = $NombreFigura;
        
        if (!empty(trim($NumRegIdTribFigura))) {
            $figuraTransporte->NumRegIdTribFigura = $NumRegIdTribFigura;
        }
        if (!empty(trim($ResidenciaFiscalFigura))) {
            $figuraTransporte->ResidenciaFiscalFigura = $ResidenciaFiscalFigura;
        }
        
        // Agregar domicilio si se proporciona
        if (!empty(trim($Calle)) || !empty(trim($Estado)) || !empty(trim($Pais)) || !empty(trim($CodigoPostal))) {
            $domicilio = new stdClass();
            $domicilio->Estado = $Estado;
            $domicilio->Pais = $Pais;
            $domicilio->CodigoPostal = $CodigoPostal;
            
            if (!empty(trim($Calle))) {
                $domicilio->Calle = $Calle;
            }
            if (!empty(trim($NumeroExterior))) {
                $domicilio->NumeroExterior = $NumeroExterior;
            }
            if (!empty(trim($NumeroInterior))) {
                $domicilio->NumeroInterior = $NumeroInterior;
            }
            if (!empty(trim($Colonia))) {
                $domicilio->Colonia = $Colonia;
            }
            if (!empty(trim($Localidad))) {
                $domicilio->Localidad = $Localidad;
            }
            if (!empty(trim($Referencia))) {
                $domicilio->Referencia = $Referencia;
            }
            if (!empty(trim($Municipio))) {
                $domicilio->Municipio = $Municipio;
            }
            
            $figuraTransporte->Domicilio = $domicilio;
        }
        
        if (!isset($this->cCartaPorte->FiguraTransporte)) {
            $this->cCartaPorte->FiguraTransporte = new stdClass();
            $this->cCartaPorte->FiguraTransporte->TiposFigura = [];
        }
        
        $this->cCartaPorte->FiguraTransporte->TiposFigura[] = $figuraTransporte;
    }

    public function AgregarParteTransporte($ParteTransporte)
    {
        if (!isset($this->cCartaPorte->FiguraTransporte)) {
            $this->cCartaPorte->FiguraTransporte = new stdClass();
            $this->cCartaPorte->FiguraTransporte->TiposFigura = [];
        }
        
        $ultimaFigura = end($this->cCartaPorte->FiguraTransporte->TiposFigura);
        if ($ultimaFigura) {
            if (!isset($ultimaFigura->PartesTransporte)) {
                $ultimaFigura->PartesTransporte = [];
            }
            $ultimaFigura->PartesTransporte[] = (object)['ParteTransporte' => $ParteTransporte];
        }
    }

    public function AgregarContenedorFerroviario($TipoContenedor, $PesoContenedorVacio, $PesoNetoMercancia)
    {
        if (!isset($this->cMercancias->TransporteFerroviario->Carro) || empty($this->cMercancias->TransporteFerroviario->Carro)) {
            throw new Exception("Debe agregar un carro antes de agregar contenedor ferroviario");
        }
        
        $contenedor = new stdClass();
        $contenedor->TipoContenedor = $TipoContenedor;
        $contenedor->PesoContenedorVacio = $PesoContenedorVacio;
        $contenedor->PesoNetoMercancia = $PesoNetoMercancia;
        
        $ultimoCarro = end($this->cMercancias->TransporteFerroviario->Carro);
        if (!isset($ultimoCarro->Contenedor)) {
            $ultimoCarro->Contenedor = [];
        }
        
        $ultimoCarro->Contenedor[] = $contenedor;
    }

    public function FinalizarCartaPorte($PesoBrutoTotal, $UnidadPeso = null, $NumTotalMercancias = null, $LogisticaInversaRecoleccionDevolucion = null, $PesoNetoTotal = null, $CargoPorTasacion = null)
    {
        // Agregar ubicaciones
        if (!empty($this->aUbicaciones)) {
            $this->cUbicaciones->Ubicacion = $this->aUbicaciones;
        }
        
        // Agregar mercancías
        if (!empty($this->aMercancias)) {
            $this->cMercancias->Mercancia = $this->aMercancias;
            $this->cMercancias->PesoBrutoTotal = $PesoBrutoTotal;
            
            if (!empty(trim($UnidadPeso))) {
                $this->cMercancias->UnidadPeso = $UnidadPeso;
            }
            if (!empty(trim($NumTotalMercancias))) {
                $this->cMercancias->NumTotalMercancias = $NumTotalMercancias;
            }
            if (!empty(trim($LogisticaInversaRecoleccionDevolucion))) {
                $this->cMercancias->LogisticaInversaRecoleccionDevolucion = $LogisticaInversaRecoleccionDevolucion;
            }
            if (!empty(trim($PesoNetoTotal))) {
                $this->cMercancias->PesoNetoTotal = $PesoNetoTotal;
            }
            if (!empty(trim($CargoPorTasacion))) {
                $this->cMercancias->CargoPorTasacion = $CargoPorTasacion;
            }
        }
        
        // Construir estructura final
        $this->cCartaPorte->Ubicaciones = $this->cUbicaciones;
        $this->cCartaPorte->Mercancias = $this->cMercancias;
        
        if (!empty($this->aRegimenesAduaneros)) {
            $this->cCartaPorte->RegimenesAduaneros = $this->cRegimenesAduaneros;
        }
    }

    public function ObtenerCartaPorte()
    {
        return $this->cCartaPorte;
    }

    public function CrearXML()
    {
        // Crear estructura XML
        $xml = new DOMDocument('1.0', 'UTF-8');
        $xml->formatOutput = true;
        $xml->preserveWhiteSpace = false;

        // Elemento raíz
        $root = $xml->createElementNS("http://www.sat.gob.mx/CartaPorte31", "cartaporte31:CartaPorte");
        $root->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
        $root->setAttribute("xmlns:cartaporte31", "http://www.sat.gob.mx/CartaPorte31");
        $root->setAttribute("xsi:schemaLocation", $this->cCartaPorte->SchemaLocalization);
        
        // Agregar atributos del elemento raíz
        $this->AgregarAtributosElemento($root, $this->cCartaPorte);
        
        // Agregar elementos hijos
        if (isset($this->cCartaPorte->RegimenesAduaneros)) {
            $this->CrearRegimenesAduanerosXML($xml, $root);
        }
        
        $this->CrearUbicacionesXML($xml, $root);
        $this->CrearMercanciasXML($xml, $root);
        
        // Agregar FiguraTransporte si existe
        if (isset($this->cCartaPorte->FiguraTransporte)) {
            $this->CrearFiguraTransporteXML($xml, $root);
        }
        
        $xml->appendChild($root);
        
        return $xml->saveXML();
    }

    private function AgregarAtributosElemento($elemento, $objeto)
    {
        foreach ($objeto as $atributo => $valor) {
            if ($atributo !== 'RegimenesAduaneros' && $atributo !== 'Ubicaciones' && $atributo !== 'Mercancias' && 
                $atributo !== 'Xmlns' && $atributo !== 'XmlnsXsi' && $atributo !== 'SchemaLocalization') {
                
                // Verificar que el valor sea una cadena antes de usar trim()
                if (is_string($valor) && !empty(trim($valor))) {
                    $elemento->setAttribute($atributo, $valor);
                } elseif (is_numeric($valor)) {
                    // Para valores numéricos, convertirlos a string
                    $elemento->setAttribute($atributo, (string)$valor);
                }
            }
        }
    }

    private function CrearRegimenesAduanerosXML($xml, $root)
    {
        $regimenesAduaneros = $xml->createElement("cartaporte31:RegimenesAduaneros");
        
        foreach ($this->cCartaPorte->RegimenesAduaneros->RegimenAduaneroCCP as $regimen) {
            $regimenElement = $xml->createElement("cartaporte31:RegimenAduaneroCCP");
            $regimenElement->setAttribute("RegimenAduanero", $regimen->RegimenAduanero);
            $regimenesAduaneros->appendChild($regimenElement);
        }
        
        $root->appendChild($regimenesAduaneros);
    }

    private function CrearUbicacionesXML($xml, $root)
    {
        $ubicaciones = $xml->createElement("cartaporte31:Ubicaciones");
        
        foreach ($this->cCartaPorte->Ubicaciones->Ubicacion as $ubicacion) {
            $ubicacionElement = $xml->createElement("cartaporte31:Ubicacion");
            $this->AgregarAtributosElemento($ubicacionElement, $ubicacion);
            
            if (isset($ubicacion->Domicilio)) {
                $domicilioElement = $xml->createElement("cartaporte31:Domicilio");
                $this->AgregarAtributosElemento($domicilioElement, $ubicacion->Domicilio);
                $ubicacionElement->appendChild($domicilioElement);
            }
            
            $ubicaciones->appendChild($ubicacionElement);
        }
        
        $root->appendChild($ubicaciones);
    }

    private function CrearMercanciasXML($xml, $root)
    {
        $mercancias = $xml->createElement("cartaporte31:Mercancias");
        $mercancias->setAttribute("PesoBrutoTotal", $this->cCartaPorte->Mercancias->PesoBrutoTotal);
        
        foreach ($this->cCartaPorte->Mercancias->Mercancia as $mercancia) {
            $mercanciaElement = $xml->createElement("cartaporte31:Mercancia");
            $this->AgregarAtributosElemento($mercanciaElement, $mercancia);
            
            // Documentación aduanera
            if (isset($mercancia->DocumentacionAduanera)) {
                foreach ($mercancia->DocumentacionAduanera as $doc) {
                    $docElement = $xml->createElement("cartaporte31:DocumentacionAduanera");
                    $this->AgregarAtributosElemento($docElement, $doc);
                    $mercanciaElement->appendChild($docElement);
                }
            }
            
            // Guías de identificación
            if (isset($mercancia->GuiasIdentificacion)) {
                foreach ($mercancia->GuiasIdentificacion as $guia) {
                    $guiaElement = $xml->createElement("cartaporte31:GuiasIdentificacion");
                    $this->AgregarAtributosElemento($guiaElement, $guia);
                    $mercanciaElement->appendChild($guiaElement);
                }
            }
            
            // Cantidades transporta
            if (isset($mercancia->CantidadTransporta)) {
                foreach ($mercancia->CantidadTransporta as $cantidad) {
                    $cantidadElement = $xml->createElement("cartaporte31:CantidadTransporta");
                    $this->AgregarAtributosElemento($cantidadElement, $cantidad);
                    $mercanciaElement->appendChild($cantidadElement);
                }
            }
            
            // Detalle de mercancía
            if (isset($mercancia->DetalleMercancia)) {
                $detalleElement = $xml->createElement("cartaporte31:DetalleMercancia");
                $this->AgregarAtributosElemento($detalleElement, $mercancia->DetalleMercancia);
                $mercanciaElement->appendChild($detalleElement);
            }
            
            $mercancias->appendChild($mercanciaElement);
        }
        
        // Autotransporte
        if (isset($this->cCartaPorte->Mercancias->Autotransporte)) {
            $this->CrearAutotransporteXML($xml, $mercancias);
        }
        
        // Transporte marítimo
        if (isset($this->cCartaPorte->Mercancias->TransporteMaritimo)) {
            $this->CrearTransporteMaritimoXML($xml, $mercancias);
        }
        
        // Transporte aéreo
        if (isset($this->cCartaPorte->Mercancias->TransporteAereo)) {
            $this->CrearTransporteAereoXML($xml, $mercancias);
        }
        
        // Transporte ferroviario
        if (isset($this->cCartaPorte->Mercancias->TransporteFerroviario)) {
            $this->CrearTransporteFerroviarioXML($xml, $mercancias);
        }
        
        $root->appendChild($mercancias);
    }

    private function CrearAutotransporteXML($xml, $mercancias)
    {
        $autotransporte = $this->cCartaPorte->Mercancias->Autotransporte;
        $autotransporteElement = $xml->createElement("cartaporte31:Autotransporte");
        $this->AgregarAtributosElemento($autotransporteElement, $autotransporte);
        
        // Identificación vehicular
        $identificacionElement = $xml->createElement("cartaporte31:IdentificacionVehicular");
        $this->AgregarAtributosElemento($identificacionElement, $autotransporte->IdentificacionVehicular);
        $autotransporteElement->appendChild($identificacionElement);
        
        // Seguros
        $segurosElement = $xml->createElement("cartaporte31:Seguros");
        $this->AgregarAtributosElemento($segurosElement, $autotransporte->Seguros);
        $autotransporteElement->appendChild($segurosElement);
        
        // Remolques
        if (isset($autotransporte->Remolques)) {
            $remolquesElement = $xml->createElement("cartaporte31:Remolques");
            foreach ($autotransporte->Remolques->Remolque as $remolque) {
                $remolqueElement = $xml->createElement("cartaporte31:Remolque");
                $this->AgregarAtributosElemento($remolqueElement, $remolque);
                $remolquesElement->appendChild($remolqueElement);
            }
            $autotransporteElement->appendChild($remolquesElement);
        }
        
        $mercancias->appendChild($autotransporteElement);
    }

    private function CrearTransporteMaritimoXML($xml, $mercancias)
    {
        $transporteMaritimo = $this->cCartaPorte->Mercancias->TransporteMaritimo;
        $transporteMaritimoElement = $xml->createElement("cartaporte31:TransporteMaritimo");
        $this->AgregarAtributosElemento($transporteMaritimoElement, $transporteMaritimo);
        
        // Contenedores
        if (isset($transporteMaritimo->Contenedor)) {
            foreach ($transporteMaritimo->Contenedor as $contenedor) {
                $contenedorElement = $xml->createElement("cartaporte31:Contenedor");
                $this->AgregarAtributosElemento($contenedorElement, $contenedor);
                $transporteMaritimoElement->appendChild($contenedorElement);
            }
        }
        
        $mercancias->appendChild($transporteMaritimoElement);
    }

    private function CrearTransporteAereoXML($xml, $mercancias)
    {
        $transporteAereo = $this->cCartaPorte->Mercancias->TransporteAereo;
        $transporteAereoElement = $xml->createElement("cartaporte31:TransporteAereo");
        $this->AgregarAtributosElemento($transporteAereoElement, $transporteAereo);
        $mercancias->appendChild($transporteAereoElement);
    }

    private function CrearTransporteFerroviarioXML($xml, $mercancias)
    {
        $transporteFerroviario = $this->cCartaPorte->Mercancias->TransporteFerroviario;
        $transporteFerroviarioElement = $xml->createElement("cartaporte31:TransporteFerroviario");
        $this->AgregarAtributosElemento($transporteFerroviarioElement, $transporteFerroviario);
        
        // Derechos de paso
        if (isset($transporteFerroviario->DerechosDePaso)) {
            foreach ($transporteFerroviario->DerechosDePaso as $derechos) {
                $derechosElement = $xml->createElement("cartaporte31:DerechosDePaso");
                $this->AgregarAtributosElemento($derechosElement, $derechos);
                $transporteFerroviarioElement->appendChild($derechosElement);
            }
        }
        
        // Carros
        if (isset($transporteFerroviario->Carro)) {
            foreach ($transporteFerroviario->Carro as $carro) {
                $carroElement = $xml->createElement("cartaporte31:Carro");
                $this->AgregarAtributosElemento($carroElement, $carro);
                
                // Contenedores del carro
                if (isset($carro->Contenedor)) {
                    foreach ($carro->Contenedor as $contenedor) {
                        $contenedorElement = $xml->createElement("cartaporte31:Contenedor");
                        $this->AgregarAtributosElemento($contenedorElement, $contenedor);
                        $carroElement->appendChild($contenedorElement);
                    }
                }
                
                $transporteFerroviarioElement->appendChild($carroElement);
            }
        }
        
        $mercancias->appendChild($transporteFerroviarioElement);
    }

    private function CrearFiguraTransporteXML($xml, $root)
    {
        $figuraTransporte = $xml->createElement("cartaporte31:FiguraTransporte");
        
        foreach ($this->cCartaPorte->FiguraTransporte->TiposFigura as $figura) {
            $figuraElement = $xml->createElement("cartaporte31:TiposFigura");
            $this->AgregarAtributosElemento($figuraElement, $figura);
            
            // Agregar PartesTransporte si existen
            if (isset($figura->PartesTransporte)) {
                foreach ($figura->PartesTransporte as $parte) {
                    $parteElement = $xml->createElement("cartaporte31:PartesTransporte");
                    $this->AgregarAtributosElemento($parteElement, $parte);
                    $figuraElement->appendChild($parteElement);
                }
            }
            
            if (isset($figura->Domicilio)) {
                $domicilioElement = $xml->createElement("cartaporte31:Domicilio");
                $this->AgregarAtributosElemento($domicilioElement, $figura->Domicilio);
                $figuraElement->appendChild($domicilioElement);
            }
            
            $figuraTransporte->appendChild($figuraElement);
        }
        
        $root->appendChild($figuraTransporte);
    }

    /**
     * Crea el XML de Carta Porte con validaciones de Finkok
     * 
     * @param string $FinkokUser Usuario de Finkok
     * @param string $FinkokPass Contraseña de Finkok
     * @param string &$Errores Variable para almacenar errores
     * @param string $Ruta Ruta donde guardar el XML (opcional)
     * @param string $nameXML Nombre del archivo XML (opcional)
     * @param string &$ErrorE Variable para almacenar errores adicionales (opcional)
     * @return bool|string Retorna el XML como string si es exitoso, false si hay errores
     */
    public function CrearCartaPorteXML($FinkokUser, $FinkokPass, &$Errores, $Ruta = null, $nameXML = null, &$ErrorE = null)
    {
        // Validar que se hayan proporcionado credenciales
        if (empty(trim($FinkokUser)) || empty(trim($FinkokPass))) {
            $Errores = "Error: Se requieren tanto el usuario como la contraseña de Finkok";
            return false;
        }

        $RFC = $this->cEmisor->Rfc;

        // Validar credenciales primero usando el servicio de Utilities
        $authResponse = $this->validateFinkokCredentials($FinkokUser, $FinkokPass);
        
        // Verificar si la autenticación fue exitosa
        if ($authResponse['valid'] !== true) {
            $errorMessage = isset($authResponse['message']) 
                ? 'Error de autenticación: ' . trim($authResponse['message']) . PHP_EOL
                : 'Error desconocido al validar las credenciales' . PHP_EOL;
            
            $Errores = $errorMessage;
            return false;
        }
        
        // Validar el RFC contra el servicio de Finkok
        $rfcValidation = $this->GetMethod($FinkokUser, $FinkokPass, $RFC);
        
        // Verificar si la validación del RFC fue exitosa
        if (!isset($rfcValidation['success']) || $rfcValidation['success'] !== true) {
            $errorMessage = $rfcValidation['message'] ?? 'Error desconocido al validar el RFC';
            $Errores = $errorMessage;
            return false;
        }
        
        // Verificar el estado del RFC
        if (isset($rfcValidation['status'])) {
            $status = strtoupper($rfcValidation['status']);
            
            // Si el RFC está inactivo
            if ($status === 'I') {
                $errorMsg = 'Error: El RFC emisor se encuentra inactivo en la cuenta';
                $Errores = $errorMsg;
                return false;
            }
            
            // Si el RFC no está activo (A)
            if ($status !== 'A') {
                $errorMsg = 'Error: El estado del RFC no es válido (Estado: ' . $status . ')';
                $Errores = $errorMsg;
                return false;
            }
        } else {
            // Si no se pudo determinar el estado del RFC
            $errorMsg = 'Error: No se pudo determinar el estado del RFC';
            $Errores = $errorMsg;
            return false;
        }
        
        // Si todo está bien, procedemos a crear el XML
        try {
            // Configuración de rutas
            $nombreXML = $nameXML ? strtoupper(str_replace(".XML", "", $nameXML)) : "CARTA_PORTE";
            $rutaFinal = $Ruta ? rtrim($Ruta, '/') . '/' : "C:/Users/" . get_current_user() . "/Documents/" . $RFC . "/";

            if (!file_exists($rutaFinal)) {
                mkdir($rutaFinal, 0777, true);
            }

            $xmlruta = $rutaFinal . $nombreXML . ".xml";

            // Limpiar archivo existente
            if (file_exists($xmlruta)) {
                unlink($xmlruta);
            }

            // Crear estructura XML
            $xml = new DOMDocument('1.0', 'UTF-8');
            $xml->formatOutput = true;
            $xml->preserveWhiteSpace = false;

            // Elemento raíz
            $root = $xml->createElementNS("http://www.sat.gob.mx/cfd/4", "cfdi:Comprobante");
            $root->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
            $root->setAttribute("xmlns:cfdi", "http://www.sat.gob.mx/cfd/4");
            $root->setAttribute("xmlns:cartaporte31", "http://www.sat.gob.mx/CartaPorte31");
            $schemaLocation = "http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd http://www.sat.gob.mx/CartaPorte31 http://www.sat.gob.mx/sitio_internet/cfd/CartaPorte/CartaPorte31.xsd";
            $root->setAttribute("xsi:schemaLocation", $schemaLocation);
            
            // Atributos del comprobante
            $root->setAttribute("Version", $this->cComprobante->Version);
            if (isset($this->cComprobante->Serie)) {
                $root->setAttribute("Serie", $this->cComprobante->Serie);
            }
            if (isset($this->cComprobante->Folio)) {
                $root->setAttribute("Folio", $this->cComprobante->Folio);
            }
            $root->setAttribute("Fecha", $this->cComprobante->Fecha);
            $root->setAttribute("Sello", "");
            $root->setAttribute("NoCertificado", $this->cComprobante->NoCertificado);
            $root->setAttribute("Certificado", "");
            if (isset($this->cComprobante->FormaPago)) {
                $root->setAttribute("FormaPago", $this->cComprobante->FormaPago);
            }
            if (isset($this->cComprobante->CondicionesDePago)) {
                $root->setAttribute("CondicionesDePago", $this->cComprobante->CondicionesDePago);
            }
            $root->setAttribute("SubTotal", $this->cComprobante->SubTotal);
            if (isset($this->cComprobante->Descuento)) {
                $root->setAttribute("Descuento", $this->cComprobante->Descuento);
            }
            $root->setAttribute("Moneda", $this->cComprobante->Moneda);
            if (isset($this->cComprobante->TipoCambio)) {
                $root->setAttribute("TipoCambio", $this->cComprobante->TipoCambio);
            }
            $root->setAttribute("Total", $this->cComprobante->Total);
            $root->setAttribute("TipoDeComprobante", $this->cComprobante->TipoDeComprobante);
            if (isset($this->cComprobante->MetodoPago)) {
                $root->setAttribute("MetodoPago", $this->cComprobante->MetodoPago);
            }
            $root->setAttribute("LugarExpedicion", $this->cComprobante->LugarExpedicion);
            if (isset($this->cComprobante->Confirmacion)) {
                $root->setAttribute("Confirmacion", $this->cComprobante->Confirmacion);
            }
            $root->setAttribute("Exportacion", $this->cComprobante->Exportacion);
            $xml->appendChild($root);

            // Add Emisor
            $emisor = $xml->createElement("cfdi:Emisor");
            $emisor->setAttribute("Rfc", $this->cEmisor->Rfc);
            $emisor->setAttribute("Nombre", $this->cEmisor->NombreE);
            $emisor->setAttribute("RegimenFiscal", $this->cEmisor->RegimenFiscalE);
            if (isset($this->cEmisor->FacAtrAdquiriente)) {
                $emisor->setAttribute("FacAtrAdquiriente", $this->cEmisor->FacAtrAdquiriente);
            }
            $root->appendChild($emisor);

            // Add Receptor
            $receptor = $xml->createElement("cfdi:Receptor");
            $receptor->setAttribute("Rfc", $this->cReceptor->RfcR);
            $receptor->setAttribute("Nombre", $this->cReceptor->NombreR);
            $receptor->setAttribute("UsoCFDI", $this->cReceptor->UsoCFDI);
            $receptor->setAttribute("DomicilioFiscalReceptor", $this->cReceptor->DomicilioFiscalReceptor);
            $receptor->setAttribute("RegimenFiscalReceptor", $this->cReceptor->RegimenFiscalReceptor);
            if (isset($this->cReceptor->ResidenciaFiscal)) {
                $receptor->setAttribute("ResidenciaFiscal", $this->cReceptor->ResidenciaFiscal);
            }
            if (isset($this->cReceptor->NumRegIdTrib)) {
                $receptor->setAttribute("NumRegIdTrib", $this->cReceptor->NumRegIdTrib);
            }
            $root->appendChild($receptor);

            // Add Conceptos
            $conceptos = $xml->createElement("cfdi:Conceptos");
            foreach ($this->cConceptos as $concepto) {
                $conceptoElement = $xml->createElement("cfdi:Concepto");
                $conceptoElement->setAttribute("ClaveProdServ", $concepto->ClaveProdServ);
                if (!empty($concepto->NoIdentificacion)) {
                    $conceptoElement->setAttribute("NoIdentificacion", $concepto->NoIdentificacion);
                }
                $conceptoElement->setAttribute("Cantidad", $concepto->Cantidad);
                $conceptoElement->setAttribute("ClaveUnidad", $concepto->ClaveUnidad);
                if (!empty($concepto->Unidad)) {
                    $conceptoElement->setAttribute("Unidad", $concepto->Unidad);
                }
                $conceptoElement->setAttribute("Descripcion", $concepto->Descripcion);
                $conceptoElement->setAttribute("ValorUnitario", $concepto->ValorUnitario);
                $conceptoElement->setAttribute("Importe", $concepto->Importe);
                if (isset($concepto->Descuento)) {
                    $conceptoElement->setAttribute("Descuento", $concepto->Descuento);
                }
                $conceptoElement->setAttribute("ObjetoImp", $concepto->ObjetoImp);
                $conceptos->appendChild($conceptoElement);
            }
            $root->appendChild($conceptos);

            // Add Impuestos if they exist
            if (isset($this->cImpuestosTotales->TotalImpuestosRetenidos) || isset($this->cImpuestosTotales->TotalImpuestosTrasladados)) {
                $impuestos = $xml->createElement("cfdi:Impuestos");

                if (isset($this->cImpuestosTotales->TotalImpuestosRetenidos)) {
                    $impuestos->setAttribute("TotalImpuestosRetenidos", $this->cImpuestosTotales->TotalImpuestosRetenidos);
                }

                if (isset($this->cImpuestosTotales->TotalImpuestosTrasladados)) {
                    $impuestos->setAttribute("TotalImpuestosTrasladados", $this->cImpuestosTotales->TotalImpuestosTrasladados);
                }

                // Add Retenciones if they exist
                if (!empty($this->cImpuestosTotales->Retenciones)) {
                    $retenciones = $xml->createElement("cfdi:Retenciones");
                    foreach ($this->cImpuestosTotales->Retenciones as $retencion) {
                        $retencionElement = $xml->createElement("cfdi:Retencion");
                        $retencionElement->setAttribute("Impuesto", $retencion->Impuesto);
                        $retencionElement->setAttribute("Importe", $retencion->Importe);
                        $retenciones->appendChild($retencionElement);
                    }
                    $impuestos->appendChild($retenciones);
                }

                // Add Traslados if they exist
                if (!empty($this->cImpuestosTotales->Traslados)) {
                    $traslados = $xml->createElement("cfdi:Traslados");
                    foreach ($this->cImpuestosTotales->Traslados as $traslado) {
                        $trasladoElement = $xml->createElement("cfdi:Traslado");
                        $trasladoElement->setAttribute("Base", $traslado->Base);
                        $trasladoElement->setAttribute("Impuesto", $traslado->Impuesto);
                        $trasladoElement->setAttribute("TipoFactor", $traslado->TipoFactor);
                        $trasladoElement->setAttribute("TasaOCuota", $traslado->TasaOCuota);
                        $trasladoElement->setAttribute("Importe", $traslado->Importe);
                        $traslados->appendChild($trasladoElement);
                    }
                    $impuestos->appendChild($traslados);
                }

                $root->appendChild($impuestos);
            }

            // Add Complemento
            $complemento = $xml->createElement("cfdi:Complemento");
            
            // Add Carta Porte
            $cartaPorte = $xml->createElement("cartaporte31:CartaPorte");
            $cartaPorte->setAttribute("Version", $this->cCartaPorte->Version);
            $cartaPorte->setAttribute("IdCCP", $this->cCartaPorte->IdCCP);
            $cartaPorte->setAttribute("TranspInternac", $this->cCartaPorte->TranspInternac);
            if (isset($this->cCartaPorte->EntradaSalidaMerc)) {
                $cartaPorte->setAttribute("EntradaSalidaMerc", $this->cCartaPorte->EntradaSalidaMerc);
            }
            if (isset($this->cCartaPorte->PaisOrigenDestino)) {
                $cartaPorte->setAttribute("PaisOrigenDestino", $this->cCartaPorte->PaisOrigenDestino);
            }
            if (isset($this->cCartaPorte->ViaEntradaSalida)) {
                $cartaPorte->setAttribute("ViaEntradaSalida", $this->cCartaPorte->ViaEntradaSalida);
            }
            if (isset($this->cCartaPorte->TotalDistRec)) {
                $cartaPorte->setAttribute("TotalDistRec", $this->cCartaPorte->TotalDistRec);
            }
            if (isset($this->cCartaPorte->RegistroISTMO)) {
                $cartaPorte->setAttribute("RegistroISTMO", $this->cCartaPorte->RegistroISTMO);
            }
            if (isset($this->cCartaPorte->UbicacionPoloOrigen)) {
                $cartaPorte->setAttribute("UbicacionPoloOrigen", $this->cCartaPorte->UbicacionPoloOrigen);
            }
            if (isset($this->cCartaPorte->UbicacionPoloDestino)) {
                $cartaPorte->setAttribute("UbicacionPoloDestino", $this->cCartaPorte->UbicacionPoloDestino);
            }

            // Add RegimenesAduaneros if they exist
            if (isset($this->cCartaPorte->RegimenesAduaneros)) {
                $this->CrearRegimenesAduanerosXML($xml, $cartaPorte);
            }

            // Add Ubicaciones
            $this->CrearUbicacionesXML($xml, $cartaPorte);

            // Add Mercancias
            $this->CrearMercanciasXML($xml, $cartaPorte);

            // Add FiguraTransporte if exists
            if (isset($this->cCartaPorte->FiguraTransporte)) {
                $this->CrearFiguraTransporteXML($xml, $cartaPorte);
            }

            $complemento->appendChild($cartaPorte);
            $root->appendChild($complemento);

            // Guardar XML
            $xml->save($xmlruta);

            return true;
        } catch (Exception $ex) {
            $ErrorE = $ex;
            $Errores = $ex->getMessage();
            return false;
        }
    }

    private function GetMethod($username, $password, $taxpayer_id)
    {
        try {
            // URL del servicio de registro de Finkok
            $url = "https://demo-facturacion.finkok.com/servicios/soap/registration.wsdl";

            // Configuración del cliente SOAP
            $options = [
                'soap_version' => SOAP_1_1,
                'trace' => 1,
                'exceptions' => true,
                'connection_timeout' => 10,
            ];

            // Crear cliente SOAP
            $client = new SoapClient($url, $options);
            
            // Parámetros para la validación del RFC
            $params = [
                'reseller_username' => $username,
                'reseller_password' => $password,
                'taxpayer_id' => $taxpayer_id,
                'id_type' => 'on' // Para búsqueda exacta
            ];

            // Realizar la llamada al servicio
            $response = $client->__soapCall('get', [$params]);

            // Verificar si la respuesta tiene la estructura esperada
            if (isset($response->getResult->users->ResellerUser)) {
                $userData = $response->getResult->users->ResellerUser;
                $status = (string)$userData->status;
                $taxpayerId = (string)$userData->taxpayer_id;
                
                return [
                    'success' => true,
                    'status' => $status,
                    'message' => 'Validación exitosa',
                    'user_status' => $status,
                    'taxpayer_id' => $taxpayerId,
                    'credit' => $userData->credit
                ];
            }
            
            // Si hay un error en la respuesta
            if (isset($response->getResult->message)) {
                $errorMsg = (string)$response->getResult->message;
                return [
                    'success' => false,
                    'message' => $errorMsg,
                    'status' => 'error'
                ];
            }
            
            // Respuesta inesperada
            return [
                'success' => false,
                'message' => 'No se pudo procesar la respuesta del servicio de registro',
                'status' => 'error',
                'response' => $response
            ];
            
        } catch (SoapFault $e) {
            return [
                'success' => false,
                'message' => 'Error en el servicio de registro: ' . $e->getMessage(),
                'status' => 'error',
                'error_type' => 'soap_fault',
                'error_details' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error inesperado al validar el RFC: ' . $e->getMessage(),
                'status' => 'error',
                'error_type' => 'unexpected_error',
                'error_details' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ];
        }
    }

    /**
     * Valida las credenciales de Finkok usando el servicio de Utilities
     * 
     * @param string $username Usuario de Finkok
     * @param string $password Contraseña de Finkok
     * @return array Arreglo con el resultado de la validación
     */
    private function validateFinkokCredentials($username, $password)
    {
        try {
            // Usamos un código postal por defecto para la validación
            $zipcode = '61970'; // Código postal genérico para validación
            
            // URL del servicio de Utilities
            $url = "https://demo-facturacion.finkok.com/servicios/soap/utilities.wsdl";
            
            // Configuración de opciones para el cliente SOAP
            $options = [
                'soap_version' => SOAP_1_1,
                'trace' => 1,
                'exceptions' => true,
                'connection_timeout' => 10,
            ];
            
            // Creamos el cliente SOAP
            $client = new SoapClient($url, $options);
            
            // Preparamos los parámetros
            $params = [
                'username' => $username,
                'password' => $password,
                'zipcode' => $zipcode
            ];
            
            // Realizamos la llamada al servicio
            $response = $client->__soapCall("datetime", [$params]);
            
            // Verificamos si hay un error en la respuesta
            if (isset($response->datetimeResult->error)) {
                $errorMessage = (string)$response->datetimeResult->error;
                return [
                    'success' => false,
                    'message' => $errorMessage,
                    'valid' => false,
                    'response_type' => 'error_response'
                ];
            }
            
            // Si llegamos aquí, las credenciales son válidas
            $result = [
                'success' => true,
                'message' => 'Credenciales válidas',
                'valid' => true,
                'response_type' => 'success',
                'server_datetime' => isset($response->datetimeResult->datetime) 
                    ? (string)$response->datetimeResult->datetime 
                    : null
            ];
            
            return $result;
            
        } catch (SoapFault $e) {
            return [
                'success' => false,
                'message' => 'Error en el servicio de autenticación: ' . $e->getMessage(),
                'valid' => false,
                'error_type' => 'soap_fault',
                'error_details' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error inesperado: ' . $e->getMessage(),
                'valid' => false,
                'error_type' => 'unexpected_error',
                'error_details' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ];
        }
    }


}
