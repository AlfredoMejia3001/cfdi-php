<?php

class CFDI40
{
    private $cComprobante;
    private $cInformacionGlobal;
    private $cCfdiRelacionados;
    private $aCfdiRelacionados = []; // Array to store related CFDI UUIDs
    private $cEmisor;
    private $cReceptor;
    private $cConceptos = [];
    private $currentConcepto = null; // Track current concept being built
    private $aInformacionAduanera;
    private $aParte;
    private $cImpuestos;
    private $cImpuestosTotales;
    private $aImpuestosTotales = [];
    public function __construct()
    {
        $this->cComprobante = new stdClass();
        $this->cInformacionGlobal = new stdClass();
        $this->cCfdiRelacionados = new stdClass();
        $this->cEmisor = new stdClass();
        $this->cReceptor = new stdClass();
        $this->cConceptos = []; // Initialize as array
        $this->cImpuestos = new stdClass();
        $this->cImpuestosTotales = new stdClass();
    }
    public function Schemma()
    {
        return array(
            "CFDI40" => "http://www.sat.gob.mx/esquemas/cfd/4 http://www.sat.gob.mx/esquemas/cfd/4/cfdv40.xsd"
        );
    }

    public function CFDI40($NoCertificado, $Serie, $Folio, $Fecha, $SubTotal, $Moneda, $Total, $TipoDeComprobante, $Exportacion, $MetodoPago, $FormaPago, $LugarExpedicion, $CondicionesDePago, $Descuento, $TipoCambio, $Confirmacion)
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
    public function AgregarInformacionGlobal($Periodicidad, $Meses, $Anio)
    {
        $this->cInformacionGlobal->Periodicidad = $Periodicidad;
        $this->cInformacionGlobal->Meses = $Meses;
        $this->cInformacionGlobal->Anio = $Anio;
    }


    public function AgregarCfdiRelacionado($UUID)
    {
        $this->aCfdiRelacionados[] = $UUID;
    }

    public function AgregarCfdiRelacionados($TipoRelacion)
    {
        if (!empty($this->aCfdiRelacionados)) {
            $this->cCfdiRelacionados = new stdClass();
            $this->cCfdiRelacionados->TipoRelacion = $TipoRelacion;
            $this->cCfdiRelacionados->CfdiRelacionado = array_map(function ($uuid) {
                $relacionado = new stdClass();
                $relacionado->UUID = $uuid;
                return $relacionado;
            }, $this->aCfdiRelacionados);
        }
    }
    public function AgregarEmisor($Rfc, $NombreE, $RegimenFiscalE, $FacAtrAdquiriente)
    {
        $this->cEmisor->Rfc = $Rfc;
        $this->cEmisor->NombreE = $NombreE;
        $this->cEmisor->RegimenFiscalE = $RegimenFiscalE;
        if (!empty($FacAtrAdquiriente)) {
            $this->cEmisor->FacAtrAdquiriente = $FacAtrAdquiriente;
        }
    }
    public function AgregarReceptor($RfcR, $NombreR, $RegimenFiscalReceptor, $UsoCFDI, $DomicilioFiscalReceptor, $ResidenciaFiscal, $NumRegIdTrib)
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
        // Create new concept
        $this->currentConcepto = new stdClass();
        $this->currentConcepto->ClaveProdServ = $ClaveProdServ;
        if (!empty($NoIdentificacion)) {
            $this->currentConcepto->NoIdentificacion = $NoIdentificacion;
        }
        $this->currentConcepto->Cantidad = $Cantidad;
        $this->currentConcepto->ClaveUnidad = $ClaveUnidad;
        if (!empty($Unidad)) {
            $this->currentConcepto->Unidad = $Unidad;
        }
        $this->currentConcepto->Descripcion = $Descripcion;
        $this->currentConcepto->ValorUnitario = $ValorUnitario;
        $this->currentConcepto->Importe = $Importe;
        if (!empty($Descuento)) {
            $this->currentConcepto->Descuento = $Descuento;
        }
        $this->currentConcepto->ObjetoImp = $ObjetoImp;
        $this->currentConcepto->Impuestos = new stdClass();

        // Add to concepts array
        $this->cConceptos[] = $this->currentConcepto;
        return $this->currentConcepto;
    }

    
    public function AgregarTraslado($Base, $Impuesto, $TipoFactor, $TasaOCuota, $Importe)
    {
        if (!$this->currentConcepto) {
            throw new Exception("No hay un concepto activo para agregar el traslado");
        }

        if (!isset($this->currentConcepto->Impuestos->Traslados)) {
            $this->currentConcepto->Impuestos->Traslados = [];
        }

        $traslado = new stdClass();
        $traslado->Base = $Base;
        $traslado->Impuesto = $Impuesto;
        $traslado->TipoFactor = $TipoFactor;
        $traslado->TasaOCuota = $TasaOCuota;
        $traslado->Importe = $Importe;

        $this->currentConcepto->Impuestos->Traslados[] = $traslado;
    }

    public function AgregarRetencion($Base, $Impuesto, $TipoFactor, $TasaOCuota, $Importe)
    {
        if (!$this->currentConcepto) {
            throw new Exception("No hay un concepto activo para agregar la retención");
        }

        if (!isset($this->currentConcepto->Impuestos->Retenciones)) {
            $this->currentConcepto->Impuestos->Retenciones = [];
        }

        $retencion = new stdClass();
        $retencion->Base = $Base;
        $retencion->Impuesto = $Impuesto;
        $retencion->TipoFactor = $TipoFactor;
        $retencion->TasaOCuota = $TasaOCuota;
        $retencion->Importe = $Importe;

        $this->currentConcepto->Impuestos->Retenciones[] = $retencion;
    }

    public function AgregarComplementoEducativo($autRVOE, $nivelEducativo, $CURP, $nombreAlumno, $rfcPago = null)
    {
        if (!$this->currentConcepto) {
            throw new Exception("No hay un concepto activo para agregar el complemento educativo");
        }

        $this->currentConcepto->complementoEducativo = new stdClass();
        $this->currentConcepto->complementoEducativo->version = "1.0";
        if (!empty($rfcPago)) {
            $this->currentConcepto->complementoEducativo->rfcPago = $rfcPago;
        }
        $this->currentConcepto->complementoEducativo->autRVOE = $autRVOE;
        $this->currentConcepto->complementoEducativo->nivelEducativo = $nivelEducativo;
        $this->currentConcepto->complementoEducativo->CURP = $CURP;
        $this->currentConcepto->complementoEducativo->nombreAlumno = $nombreAlumno;
        
        return $this->currentConcepto;
    }

    public function AgregarInformacionAduanera($numero, $aduana, $fecha)
    {
        if (!$this->currentConcepto) {
            throw new Exception("No hay un concepto activo para agregar la información aduanera");
        }

        // Initialize array if it doesn't exist
        if (!isset($this->currentConcepto->informacionAduanera)) {
            $this->currentConcepto->informacionAduanera = [];
        }

        // Create new aduanera information
        $aduanera = new stdClass();
        $aduanera->numero = $numero;
        if (!empty($aduana)) {
            $aduanera->aduana = $aduana;
        }
        $aduanera->fecha = $fecha;

        // Add to array
        $this->currentConcepto->informacionAduanera[] = $aduanera;
        return $this->currentConcepto;
    }

    public function AgregarParte($cantidad, $unidad, $noIdentificacion, $descripcion, $valorUnitario, $importe)
    {
        if (!$this->currentConcepto) {
            throw new Exception("No hay un concepto activo para agregar la parte");
        }

        // Initialize parte array if it doesn't exist
        if (!isset($this->currentConcepto->complementoVentaVehiculo)) {
            $this->currentConcepto->complementoVentaVehiculo = new stdClass();
            $this->currentConcepto->complementoVentaVehiculo->parte = [];
        }

        // Create new part
        $parte = new stdClass();
        $parte->cantidad = $cantidad;
        if (!empty($unidad)) {
            $parte->unidad = $unidad;
        }
        if (!empty($noIdentificacion)) {
            $parte->noIdentificacion = $noIdentificacion;
        }
        $parte->descripcion = $descripcion;
        if (!empty($valorUnitario)) {
            $parte->valorUnitario = $valorUnitario;
        }
        if (!empty($importe)) {
            $parte->importe = $importe;
        }
        $parte->informacionAduanera = []; // Initialize empty array for aduanera info

        // Add part to array
        $this->currentConcepto->complementoVentaVehiculo->parte[] = $parte;
        return $this->currentConcepto;
    }

    public function AgregarInformacionAduaneraAParte($numero, $aduana, $fecha)
    {
        if (!$this->currentConcepto) {
            throw new Exception("No hay un concepto activo para agregar la información aduanera a parte");
        }

        // Get the last part added
        if (!isset($this->currentConcepto->complementoVentaVehiculo) || 
            !isset($this->currentConcepto->complementoVentaVehiculo->parte) || 
            empty($this->currentConcepto->complementoVentaVehiculo->parte)) {
            throw new Exception("No hay partes definidas para agregar información aduanera");
        }

        $lastPart = &$this->currentConcepto->complementoVentaVehiculo->parte[count($this->currentConcepto->complementoVentaVehiculo->parte) - 1];

        // Create new aduanera information for part
        $aduanera = new stdClass();
        $aduanera->numero = $numero;
        if (!empty($aduana)) {
            $aduanera->aduana = $aduana;
        }
        $aduanera->fecha = $fecha;

        // Add to part's aduanera information
        $lastPart->informacionAduanera[] = $aduanera;
        return $this->currentConcepto;
    }

    public function AgregarComplementoVentaVehiculo($claveVehicular, $niv)
    {
        if (!$this->currentConcepto) {
            throw new Exception("No hay un concepto activo para agregar el complemento de venta de vehículo");
        }

        // Initialize complementoVentaVehiculo if it doesn't exist
        if (!isset($this->currentConcepto->complementoVentaVehiculo)) {
            $this->currentConcepto->complementoVentaVehiculo = new stdClass();
        }

        // Set required attributes
        $this->currentConcepto->complementoVentaVehiculo->version = "1.1";
        $this->currentConcepto->complementoVentaVehiculo->claveVehicular = $claveVehicular;
        $this->currentConcepto->complementoVentaVehiculo->niv = $niv;

        // Initialize arrays for optional nodes
        if (!isset($this->currentConcepto->complementoVentaVehiculo->informacionAduanera)) {
            $this->currentConcepto->complementoVentaVehiculo->informacionAduanera = [];
        }

        if (!isset($this->currentConcepto->complementoVentaVehiculo->parte)) {
            $this->currentConcepto->complementoVentaVehiculo->parte = [];
        }

        // Add any existing information aduanera at vehicle level
        if (isset($this->currentConcepto->informacionAduanera)) {
            foreach ($this->currentConcepto->informacionAduanera as $info) {
                $this->currentConcepto->complementoVentaVehiculo->informacionAduanera[] = $info;
            }
        }

        return $this->currentConcepto;
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

    private function CrearXML()
    {
        $comprobante = $this->cComprobante;
        $comprobante->InformacionGlobal = $this->cInformacionGlobal;
        $comprobante->CfdiRelacionados = $this->cCfdiRelacionados;
        $comprobante->Emisor = $this->cEmisor;
        $comprobante->Receptor = $this->cReceptor;
        $comprobante->Conceptos = $this->cConceptos;
        $comprobante->Impuestos = $this->cImpuestos;
        $comprobante->ImpuestosTotales = $this->cImpuestosTotales;

        return $comprobante;
    }

    public function CrearFacturaXML($FinkokUser, $FinkokPass, &$Errores, $Ruta = null, $nameXML = null, &$ErrorE = null)
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
        $c = $this->CrearXML();

        try {
            // Configuración de rutas
            $nombreXML = $nameXML ? strtoupper(str_replace(".XML", "", $nameXML)) : "CFDI";
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
            $root->setAttribute("xmlns:iedu", "http://www.sat.gob.mx/iedu");
            $root->setAttribute("xmlns:ventavehiculos", "http://www.sat.gob.mx/ventavehiculos");
            $schemaLocation = "http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd http://www.sat.gob.mx/iedu http://www.sat.gob.mx/sitio_internet/cfd/iedu/iedu.xsd http://www.sat.gob.mx/ventavehiculos http://www.sat.gob.mx/sitio_internet/cfd/ventavehiculos/ventavehiculos11.xsd";
            
            $root->setAttribute("xsi:schemaLocation", $schemaLocation);
            $root->setAttribute("Version", $this->cComprobante->Version);
            if (isset($this->cComprobante->Serie)) {
                $root->setAttribute("Serie", $this->cComprobante->Serie);
            }
            if (isset($this->cComprobante->Folio)) {
                $root->setAttribute("Folio", $this->cComprobante->Folio);
            }
            // Set current date if Fecha is empty or null, using Mexico City timezone
            if (empty($this->cComprobante->Fecha)) {
                $timezone = new DateTimeZone('America/Mexico_City');
                $fecha = new DateTime('now', $timezone);
                $fecha = $fecha->format('Y-m-d\TH:i:s');
            } else {
                $fecha = $this->cComprobante->Fecha;
            }
            $root->setAttribute("Fecha", $fecha);
            $root->setAttribute("Sello", "");
            if (isset($this->cComprobante->FormaPago)) {
                $root->setAttribute("FormaPago", $this->cComprobante->FormaPago);
            }
            $root->setAttribute("NoCertificado", $this->cComprobante->NoCertificado);
            $root->setAttribute("Certificado", "");
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
            $root->setAttribute("MetodoPago", $this->cComprobante->MetodoPago ?? '');
            $root->setAttribute("LugarExpedicion", $this->cComprobante->LugarExpedicion);
            if (isset($this->cComprobante->Confirmacion)) {
                $root->setAttribute("Confirmacion", $this->cComprobante->Confirmacion);
            }
            $root->setAttribute("Exportacion", $this->cComprobante->Exportacion);
            $xml->appendChild($root);

            // Add Información Global if it exists
            if (isset($this->cInformacionGlobal->Periodicidad)) {
                $infoGlobal = $xml->createElement("cfdi:InformacionGlobal");
                $infoGlobal->setAttribute("Periodicidad", $this->cInformacionGlobal->Periodicidad);
                $infoGlobal->setAttribute("Meses", $this->cInformacionGlobal->Meses);
                $infoGlobal->setAttribute("Anio", $this->cInformacionGlobal->Anio);
                $root->appendChild($infoGlobal);
            }

            // Add CFDI Relacionados if they exist
            if (isset($this->cCfdiRelacionados->TipoRelacion)) {
                $cfdiRelacionados = $xml->createElement("cfdi:CfdiRelacionados");
                $cfdiRelacionados->setAttribute("TipoRelacion", $this->cCfdiRelacionados->TipoRelacion);

                // Add each related CFDI
                foreach ($this->cCfdiRelacionados->CfdiRelacionado as $relacionado) {
                    $cfdiRelacionado = $xml->createElement("cfdi:CfdiRelacionado");
                    $cfdiRelacionado->setAttribute("UUID", $relacionado->UUID);
                    $cfdiRelacionados->appendChild($cfdiRelacionado);
                }

                $root->appendChild($cfdiRelacionados);
            }

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

            // Add optional attributes if they exist
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

               

                // Add Impuestos if they exist
                if (isset($concepto->Impuestos) && (isset($concepto->Impuestos->Traslados) || isset($concepto->Impuestos->Retenciones))) {
                    $impuestos = $xml->createElement("cfdi:Impuestos");

                    // Add Traslados
                    if (isset($concepto->Impuestos->Traslados)) {
                        $traslados = $xml->createElement("cfdi:Traslados");
                        foreach ($concepto->Impuestos->Traslados as $traslado) {
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

                    // Add Retenciones
                    if (isset($concepto->Impuestos->Retenciones)) {
                        $retenciones = $xml->createElement("cfdi:Retenciones");
                        foreach ($concepto->Impuestos->Retenciones as $retencion) {
                            $retencionElement = $xml->createElement("cfdi:Retencion");
                            $retencionElement->setAttribute("Base", $retencion->Base);
                            $retencionElement->setAttribute("Impuesto", $retencion->Impuesto);
                            $retencionElement->setAttribute("TipoFactor", $retencion->TipoFactor);
                            $retencionElement->setAttribute("TasaOCuota", $retencion->TasaOCuota);
                            $retencionElement->setAttribute("Importe", $retencion->Importe);
                            $retenciones->appendChild($retencionElement);
                        }
                        $impuestos->appendChild($retenciones);
                    }

                    $conceptoElement->appendChild($impuestos);
                }
                 // Add ComplementoEducativo if it exists
                 if (isset($concepto->complementoEducativo)) {
                    $complemento = $xml->createElement("cfdi:ComplementoConcepto");
                    $iedu = $xml->createElement("iedu:instEducativas");
                    $iedu->setAttribute("xmlns:iedu", "http://www.sat.gob.mx/iedu");
                    $iedu->setAttribute("version", $concepto->complementoEducativo->version);
                    $iedu->setAttribute("nombreAlumno", $concepto->complementoEducativo->nombreAlumno);
                    $iedu->setAttribute("CURP", $concepto->complementoEducativo->CURP);
                    $iedu->setAttribute("nivelEducativo", $concepto->complementoEducativo->nivelEducativo);
                    $iedu->setAttribute("autRVOE", $concepto->complementoEducativo->autRVOE);
                    if (!empty($concepto->complementoEducativo->rfcPago)) {
                        $iedu->setAttribute("rfcPago", $concepto->complementoEducativo->rfcPago);
                    }
                    
                    $complemento->appendChild($iedu);
                    $conceptoElement->appendChild($complemento);
                }

                // Add ComplementoVentaVehiculo if it exists
                if (isset($concepto->complementoVentaVehiculo)) {
                    $complemento = $xml->createElement("cfdi:ComplementoConcepto");
                    $ventaVehiculo = $xml->createElement("ventavehiculos:VentaVehiculos");
                    $ventaVehiculo->setAttribute("xmlns:ventavehiculos", "http://www.sat.gob.mx/ventavehiculos");
                    $ventaVehiculo->setAttribute("version", $concepto->complementoVentaVehiculo->version);
                    $ventaVehiculo->setAttribute("ClaveVehicular", $concepto->complementoVentaVehiculo->claveVehicular);
                    $ventaVehiculo->setAttribute("Niv", $concepto->complementoVentaVehiculo->niv);

                    // Add InformacionAduanera at vehicle level
                    if (isset($concepto->complementoVentaVehiculo->informacionAduanera)) {
                        foreach ($concepto->complementoVentaVehiculo->informacionAduanera as $info) {
                            $infoAduanera = $xml->createElement("ventavehiculos:InformacionAduanera");
                            $infoAduanera->setAttribute("numero", $info->numero);
                            if (isset($info->aduana)) {
                                $infoAduanera->setAttribute("aduana", $info->aduana);
                            }
                            $infoAduanera->setAttribute("fecha", $info->fecha);
                            $ventaVehiculo->appendChild($infoAduanera);
                        }
                    }

                    // Add Parte and its InformacionAduanera
                    if (isset($concepto->complementoVentaVehiculo->parte)) {
                        foreach ($concepto->complementoVentaVehiculo->parte as $parte) {
                            $parteElement = $xml->createElement("ventavehiculos:Parte");
                            $parteElement->setAttribute("cantidad", $parte->cantidad);
                            $parteElement->setAttribute("unidad", $parte->unidad);
                            $parteElement->setAttribute("noIdentificacion", $parte->noIdentificacion);
                            $parteElement->setAttribute("descripcion", $parte->descripcion);
                            $parteElement->setAttribute("valorUnitario", $parte->valorUnitario);
                            $parteElement->setAttribute("importe", $parte->importe);

                            // Add InformacionAduanera at part level
                            if (isset($parte->informacionAduanera)) {
                                foreach ($parte->informacionAduanera as $info) {
                                    $infoAduaneraParte = $xml->createElement("ventavehiculos:InformacionAduanera");
                                    $infoAduaneraParte->setAttribute("numero", $info->numero);
                                    if (isset($info->aduana)) {
                                        $infoAduaneraParte->setAttribute("aduana", $info->aduana);
                                    }
                                    $infoAduaneraParte->setAttribute("fecha", $info->fecha);
                                    $parteElement->appendChild($infoAduaneraParte);
                                }
                            }

                            $ventaVehiculo->appendChild($parteElement);
                        }
                    }
                    
                    $complemento->appendChild($ventaVehiculo);
                    $conceptoElement->appendChild($complemento);
                }

                $conceptos->appendChild($conceptoElement);
            }
            $root->appendChild($conceptos);

            // Add Impuestos Totales if they exist
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

            $tempFile = tempnam(sys_get_temp_dir(), 'reten_');
            $xml->save($tempFile);

            // Guardar XML final
            $xml->save($xmlruta);

            // Limpiar nodos vacíos
            $this->RemoveEmptyNodes($xml);
            $xml->save($xmlruta);

            // Eliminar archivo temporal
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }

            return true;
        } catch (Exception $ex) {
            if (isset($tempFile) && file_exists($tempFile)) {
                @unlink($tempFile);
            }
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

    private function CreateSoapEnvelope($userName, $password, $taxpayer_id)
    {
        return '<?xml version="1.0" encoding="utf-8"?>
           <soapenv:Envelope xmlns:reg="http://facturacion.finkok.com/registration" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
              <soapenv:Header/>
              <soapenv:Body>
                  <reg:get>
                      <reg:reseller_username>' . $userName . '</reg:reseller_username>
                      <reg:reseller_password>' . $password . '</reg:reseller_password>
                      <reg:taxpayer_id>' . $taxpayer_id . '</reg:taxpayer_id>
                  </reg:get>
              </soapenv:Body>
           </soapenv:Envelope>';
    }

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

    private function RemoveEmptyNodes(DOMDocument &$dom)
    {
        $xpath = new DOMXPath($dom);
        $nodes = $xpath->query('//*[not(node())]');

        foreach ($nodes as $node) {
            if ($node->attributes->length == 0) {
                $node->parentNode->removeChild($node);
            }
        }
    }


}
