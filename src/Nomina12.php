<?php

class Nomina12
{
    // Propiedades para CFDI principal
    private $cComprobante;
    private $cEmisor;
    private $cReceptor;
    private $cConceptos = [];
    private $currentConcepto = null;
    private $cCfdiRelacionados;
    private $aCfdiRelacionados = [];

    // Propiedades para complemento de Nómina
    private $cNomina;
    private $cEmisorNomina;
    private $cReceptorNomina;
    private $cPercepciones;
    private $aPercepciones = [];
    private $cDeducciones;
    private $aDeducciones = [];
    private $cOtrosPagos;
    private $aOtrosPagos = [];
    private $cIncapacidades;
    private $aIncapacidades = [];

    public function __construct()
    {
        $this->cComprobante = new stdClass();
        $this->cEmisor = new stdClass();
        $this->cReceptor = new stdClass();
        $this->cConceptos = [];
        $this->cCfdiRelacionados = new stdClass();
        $this->cNomina = new stdClass();
        $this->cEmisorNomina = new stdClass();
        $this->cReceptorNomina = new stdClass();
        $this->cPercepciones = new stdClass();
        $this->cDeducciones = new stdClass();
        $this->cOtrosPagos = new stdClass();
        $this->cIncapacidades = new stdClass();
    }

    public function Schemma()
    {
        return array(
            "Nomina12" => "http://www.sat.gob.mx/nomina12 http://www.sat.gob.mx/sitio_internet/cfd/nomina/nomina12.xsd"
        );
    }

    // Métodos para CFDI principal
    public function CFDI40($NoCertificado, $Serie, $Folio, $Fecha, $SubTotal, $Moneda, $Total, $TipoDeComprobante, $Exportacion, $MetodoPago, $FormaPago, $LugarExpedicion, $CondicionesDePago = null, $Descuento = null, $TipoCambio = null, $Confirmacion = null)
    {
        $this->cComprobante->Version = "4.0";
        $this->cComprobante->SchemaLocalization = $this->Schemma()["Nomina12"];
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

        // Add to concepts array
        $this->cConceptos[] = $this->currentConcepto;
        return $this->currentConcepto;
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

    // Métodos para complemento de Nómina
    public function Nomina12($Version = "1.2", $TipoNomina, $FechaPago, $FechaInicialPago, $FechaFinalPago, $NumDiasPagados, $TotalPercepciones = null, $TotalDeducciones = null, $TotalOtrosPagos = null)
    {
        $this->cNomina->Version = $Version;
        $this->cNomina->TipoNomina = $TipoNomina;
        $this->cNomina->FechaPago = $FechaPago;
        $this->cNomina->FechaInicialPago = $FechaInicialPago;
        $this->cNomina->FechaFinalPago = $FechaFinalPago;
        $this->cNomina->NumDiasPagados = $NumDiasPagados;
        
        if (!empty($TotalPercepciones)) {
            $this->cNomina->TotalPercepciones = $TotalPercepciones;
        }
        if (!empty($TotalDeducciones)) {
            $this->cNomina->TotalDeducciones = $TotalDeducciones;
        }
        if (!empty($TotalOtrosPagos)) {
            $this->cNomina->TotalOtrosPagos = $TotalOtrosPagos;
        }
    }

    public function AgregarEmisorNomina($Curp = null, $RegistroPatronal = null, $RfcPatronOrigen = null)
    {
        if (!empty($Curp)) {
            $this->cEmisorNomina->Curp = $Curp;
        }
        if (!empty($RegistroPatronal)) {
            $this->cEmisorNomina->RegistroPatronal = $RegistroPatronal;
        }
        if (!empty($RfcPatronOrigen)) {
            $this->cEmisorNomina->RfcPatronOrigen = $RfcPatronOrigen;
        }
    }

    public function AgregarEntidadSNCF($OrigenRecurso, $MontoRecursoPropio = null)
    {
        $this->cEmisorNomina->EntidadSNCF = new stdClass();
        $this->cEmisorNomina->EntidadSNCF->OrigenRecurso = $OrigenRecurso;
        if (!empty($MontoRecursoPropio)) {
            $this->cEmisorNomina->EntidadSNCF->MontoRecursoPropio = $MontoRecursoPropio;
        }
    }

    public function AgregarReceptorNomina($Curp, $NumSeguridadSocial = null, $FechaInicioRelLaboral = null, $Antigüedad = null, $TipoContrato, $Sindicalizado = null, $TipoJornada = null, $TipoRegimen, $NumEmpleado, $Departamento = null, $Puesto = null, $RiesgoPuesto = null, $PeriodicidadPago, $Banco = null, $CuentaBancaria = null, $SalarioBaseCotApor = null, $SalarioDiarioIntegrado = null, $ClaveEntFed)
    {
        $this->cReceptorNomina->Curp = $Curp;
        $this->cReceptorNomina->TipoContrato = $TipoContrato;
        $this->cReceptorNomina->TipoRegimen = $TipoRegimen;
        $this->cReceptorNomina->NumEmpleado = $NumEmpleado;
        $this->cReceptorNomina->PeriodicidadPago = $PeriodicidadPago;
        $this->cReceptorNomina->ClaveEntFed = $ClaveEntFed;
        
        if (!empty($NumSeguridadSocial)) {
            $this->cReceptorNomina->NumSeguridadSocial = $NumSeguridadSocial;
        }
        if (!empty($FechaInicioRelLaboral)) {
            $this->cReceptorNomina->FechaInicioRelLaboral = $FechaInicioRelLaboral;
        }
        if (!empty($Antigüedad)) {
            $this->cReceptorNomina->Antigüedad = $Antigüedad;
        }
        if (!empty($Sindicalizado)) {
            $this->cReceptorNomina->Sindicalizado = $Sindicalizado;
        }
        if (!empty($TipoJornada)) {
            $this->cReceptorNomina->TipoJornada = $TipoJornada;
        }
        if (!empty($Departamento)) {
            $this->cReceptorNomina->Departamento = $Departamento;
        }
        if (!empty($Puesto)) {
            $this->cReceptorNomina->Puesto = $Puesto;
        }
        if (!empty($RiesgoPuesto)) {
            $this->cReceptorNomina->RiesgoPuesto = $RiesgoPuesto;
        }
        if (!empty($Banco)) {
            $this->cReceptorNomina->Banco = $Banco;
        }
        if (!empty($CuentaBancaria)) {
            $this->cReceptorNomina->CuentaBancaria = $CuentaBancaria;
        }
        if (!empty($SalarioBaseCotApor)) {
            $this->cReceptorNomina->SalarioBaseCotApor = $SalarioBaseCotApor;
        }
        if (!empty($SalarioDiarioIntegrado)) {
            $this->cReceptorNomina->SalarioDiarioIntegrado = $SalarioDiarioIntegrado;
        }
    }

    public function AgregarSubContratacion($RfcLabora, $PorcentajeTiempo)
    {
        if (!isset($this->cReceptorNomina->SubContratacion)) {
            $this->cReceptorNomina->SubContratacion = [];
        }
        
        $subContratacion = new stdClass();
        $subContratacion->RfcLabora = $RfcLabora;
        $subContratacion->PorcentajeTiempo = $PorcentajeTiempo;
        
        $this->cReceptorNomina->SubContratacion[] = $subContratacion;
    }

    public function AgregarPercepcion($TipoPercepcion, $Clave, $Concepto, $ImporteGravado, $ImporteExento, $AccionesOTitulos = null, $HorasExtra = null)
    {
        $percepcion = new stdClass();
        $percepcion->TipoPercepcion = $TipoPercepcion;
        $percepcion->Clave = $Clave;
        $percepcion->Concepto = $Concepto;
        $percepcion->ImporteGravado = $ImporteGravado;
        $percepcion->ImporteExento = $ImporteExento;
        
        if (!empty($AccionesOTitulos)) {
            $percepcion->AccionesOTitulos = $AccionesOTitulos;
        }
        if (!empty($HorasExtra)) {
            $percepcion->HorasExtra = $HorasExtra;
        }
        
        $this->aPercepciones[] = $percepcion;
    }

    public function AgregarAccionesOTitulos($ValorMercado, $PrecioAlOtorgarse)
    {
        return [
            'ValorMercado' => $ValorMercado,
            'PrecioAlOtorgarse' => $PrecioAlOtorgarse
        ];
    }

    public function AgregarHorasExtra($Dias, $TipoHoras, $HorasExtra, $ImportePagado)
    {
        return [
            'Dias' => $Dias,
            'TipoHoras' => $TipoHoras,
            'HorasExtra' => $HorasExtra,
            'ImportePagado' => $ImportePagado
        ];
    }

    public function AgregarJubilacionPensionRetiro($TotalUnaExhibicion = null, $TotalParcialidad = null, $MontoDiario = null, $IngresoAcumulable, $IngresoNoAcumulable)
    {
        $jubilacion = new stdClass();
        $jubilacion->IngresoAcumulable = $IngresoAcumulable;
        $jubilacion->IngresoNoAcumulable = $IngresoNoAcumulable;
        
        if (!empty($TotalUnaExhibicion)) {
            $jubilacion->TotalUnaExhibicion = $TotalUnaExhibicion;
        }
        if (!empty($TotalParcialidad)) {
            $jubilacion->TotalParcialidad = $TotalParcialidad;
        }
        if (!empty($MontoDiario)) {
            $jubilacion->MontoDiario = $MontoDiario;
        }
        
        $this->cPercepciones->JubilacionPensionRetiro = $jubilacion;
    }

    public function AgregarSeparacionIndemnizacion($TotalPagado, $NumAñosServicio, $UltimoSueldoMensOrd, $IngresoAcumulable, $IngresoNoAcumulable)
    {
        $separacion = new stdClass();
        $separacion->TotalPagado = $TotalPagado;
        $separacion->NumAñosServicio = $NumAñosServicio;
        $separacion->UltimoSueldoMensOrd = $UltimoSueldoMensOrd;
        $separacion->IngresoAcumulable = $IngresoAcumulable;
        $separacion->IngresoNoAcumulable = $IngresoNoAcumulable;
        
        $this->cPercepciones->SeparacionIndemnizacion = $separacion;
    }

    public function AgregarDeduccion($TipoDeduccion, $Clave, $Concepto, $Importe)
    {
        $deduccion = new stdClass();
        $deduccion->TipoDeduccion = $TipoDeduccion;
        $deduccion->Clave = $Clave;
        $deduccion->Concepto = $Concepto;
        $deduccion->Importe = $Importe;
        
        $this->aDeducciones[] = $deduccion;
    }

    public function AgregarOtroPago($TipoOtroPago, $Clave, $Concepto, $Importe, $SubsidioAlEmpleo = null, $CompensacionSaldosAFavor = null)
    {
        $otroPago = new stdClass();
        $otroPago->TipoOtroPago = $TipoOtroPago;
        $otroPago->Clave = $Clave;
        $otroPago->Concepto = $Concepto;
        $otroPago->Importe = $Importe;
        
        if (!empty($SubsidioAlEmpleo)) {
            $otroPago->SubsidioAlEmpleo = $SubsidioAlEmpleo;
        }
        if (!empty($CompensacionSaldosAFavor)) {
            $otroPago->CompensacionSaldosAFavor = $CompensacionSaldosAFavor;
        }
        
        $this->aOtrosPagos[] = $otroPago;
    }

    public function AgregarSubsidioAlEmpleo($SubsidioCausado)
    {
        return [
            'SubsidioCausado' => $SubsidioCausado
        ];
    }

    public function AgregarCompensacionSaldosAFavor($SaldoAFavor, $Año, $RemanenteSalFav)
    {
        return [
            'SaldoAFavor' => $SaldoAFavor,
            'Año' => $Año,
            'RemanenteSalFav' => $RemanenteSalFav
        ];
    }

    public function AgregarIncapacidad($DiasIncapacidad, $TipoIncapacidad, $ImporteMonetario = null)
    {
        $incapacidad = new stdClass();
        $incapacidad->DiasIncapacidad = $DiasIncapacidad;
        $incapacidad->TipoIncapacidad = $TipoIncapacidad;
        
        if (!empty($ImporteMonetario)) {
            $incapacidad->ImporteMonetario = $ImporteMonetario;
        }
        
        $this->aIncapacidades[] = $incapacidad;
    }

    public function FinalizarNomina($TotalSueldos = null, $TotalSeparacionIndemnizacion = null, $TotalJubilacionPensionRetiro = null, $TotalGravado, $TotalExento, $TotalOtrasDeducciones = null, $TotalImpuestosRetenidos = null)
    {
        // Configurar percepciones
        if (!empty($this->aPercepciones)) {
            $this->cPercepciones->Percepcion = $this->aPercepciones;
            $this->cPercepciones->TotalGravado = $TotalGravado;
            $this->cPercepciones->TotalExento = $TotalExento;
            
            if (!empty($TotalSueldos)) {
                $this->cPercepciones->TotalSueldos = $TotalSueldos;
            }
            if (!empty($TotalSeparacionIndemnizacion)) {
                $this->cPercepciones->TotalSeparacionIndemnizacion = $TotalSeparacionIndemnizacion;
            }
            if (!empty($TotalJubilacionPensionRetiro)) {
                $this->cPercepciones->TotalJubilacionPensionRetiro = $TotalJubilacionPensionRetiro;
            }
        }

        // Configurar deducciones
        if (!empty($this->aDeducciones)) {
            $this->cDeducciones->Deduccion = $this->aDeducciones;
            
            if (!empty($TotalOtrasDeducciones)) {
                $this->cDeducciones->TotalOtrasDeducciones = $TotalOtrasDeducciones;
            }
            if (!empty($TotalImpuestosRetenidos)) {
                $this->cDeducciones->TotalImpuestosRetenidos = $TotalImpuestosRetenidos;
            }
        }

        // Configurar otros pagos
        if (!empty($this->aOtrosPagos)) {
            $this->cOtrosPagos->OtroPago = $this->aOtrosPagos;
        }

        // Configurar incapacidades
        if (!empty($this->aIncapacidades)) {
            $this->cIncapacidades->Incapacidad = $this->aIncapacidades;
        }

        // Construir estructura final
        $this->cNomina->Emisor = $this->cEmisorNomina;
        $this->cNomina->Receptor = $this->cReceptorNomina;
        
        if (!empty($this->aPercepciones)) {
            $this->cNomina->Percepciones = $this->cPercepciones;
        }
        if (!empty($this->aDeducciones)) {
            $this->cNomina->Deducciones = $this->cDeducciones;
        }
        if (!empty($this->aOtrosPagos)) {
            $this->cNomina->OtrosPagos = $this->cOtrosPagos;
        }
        if (!empty($this->aIncapacidades)) {
            $this->cNomina->Incapacidades = $this->cIncapacidades;
        }
    }

    public function ObtenerNomina()
    {
        return $this->cNomina;
    }

    public function CrearXML()
    {
        // Crear estructura XML
        $xml = new DOMDocument('1.0', 'UTF-8');
        $xml->formatOutput = true;
        $xml->preserveWhiteSpace = false;

        // Elemento raíz
        $root = $xml->createElementNS("http://www.sat.gob.mx/nomina12", "nomina12:Nomina");
        $root->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
        $root->setAttribute("xmlns:nomina12", "http://www.sat.gob.mx/nomina12");
        $root->setAttribute("xsi:schemaLocation", $this->cNomina->SchemaLocalization);
        
        // Agregar atributos del elemento raíz
        $this->AgregarAtributosElemento($root, $this->cNomina);
        
        // Agregar elementos hijos
        if (isset($this->cNomina->Emisor)) {
            $this->CrearEmisorNominaXML($xml, $root);
        }
        
        if (isset($this->cNomina->Receptor)) {
            $this->CrearReceptorNominaXML($xml, $root);
        }
        
        if (isset($this->cNomina->Percepciones)) {
            $this->CrearPercepcionesXML($xml, $root);
        }
        
        if (isset($this->cNomina->Deducciones)) {
            $this->CrearDeduccionesXML($xml, $root);
        }
        
        if (isset($this->cNomina->OtrosPagos)) {
            $this->CrearOtrosPagosXML($xml, $root);
        }
        
        if (isset($this->cNomina->Incapacidades)) {
            $this->CrearIncapacidadesXML($xml, $root);
        }
        
        $xml->appendChild($root);
        
        return $xml->saveXML();
    }

    private function AgregarAtributosElemento($elemento, $objeto)
    {
        foreach ($objeto as $atributo => $valor) {
            if ($atributo !== 'Emisor' && $atributo !== 'Receptor' && $atributo !== 'Percepciones' && 
                $atributo !== 'Deducciones' && $atributo !== 'OtrosPagos' && $atributo !== 'Incapacidades' && 
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

    private function CrearEmisorNominaXML($xml, $root)
    {
        $emisor = $xml->createElement("nomina12:Emisor");
        $this->AgregarAtributosElemento($emisor, $this->cNomina->Emisor);
        
        if (isset($this->cNomina->Emisor->EntidadSNCF)) {
            $entidadSNCF = $xml->createElement("nomina12:EntidadSNCF");
            $this->AgregarAtributosElemento($entidadSNCF, $this->cNomina->Emisor->EntidadSNCF);
            $emisor->appendChild($entidadSNCF);
        }
        
        $root->appendChild($emisor);
    }

    private function CrearReceptorNominaXML($xml, $root)
    {
        $receptor = $xml->createElement("nomina12:Receptor");
        $this->AgregarAtributosElemento($receptor, $this->cNomina->Receptor);
        
        if (isset($this->cNomina->Receptor->SubContratacion)) {
            foreach ($this->cNomina->Receptor->SubContratacion as $subContratacion) {
                $subContratacionElement = $xml->createElement("nomina12:SubContratacion");
                $this->AgregarAtributosElemento($subContratacionElement, $subContratacion);
                $receptor->appendChild($subContratacionElement);
            }
        }
        
        $root->appendChild($receptor);
    }

    private function CrearPercepcionesXML($xml, $root)
    {
        $percepciones = $xml->createElement("nomina12:Percepciones");
        $this->AgregarAtributosElemento($percepciones, $this->cNomina->Percepciones);
        
        foreach ($this->cNomina->Percepciones->Percepcion as $percepcion) {
            $percepcionElement = $xml->createElement("nomina12:Percepcion");
            $this->AgregarAtributosElemento($percepcionElement, $percepcion);
            
            if (isset($percepcion->AccionesOTitulos)) {
                $accionesElement = $xml->createElement("nomina12:AccionesOTitulos");
                $this->AgregarAtributosElemento($accionesElement, $percepcion->AccionesOTitulos);
                $percepcionElement->appendChild($accionesElement);
            }
            
            if (isset($percepcion->HorasExtra)) {
                foreach ($percepcion->HorasExtra as $horasExtra) {
                    $horasExtraElement = $xml->createElement("nomina12:HorasExtra");
                    $this->AgregarAtributosElemento($horasExtraElement, $horasExtra);
                    $percepcionElement->appendChild($horasExtraElement);
                }
            }
            
            $percepciones->appendChild($percepcionElement);
        }
        
        if (isset($this->cNomina->Percepciones->JubilacionPensionRetiro)) {
            $jubilacionElement = $xml->createElement("nomina12:JubilacionPensionRetiro");
            $this->AgregarAtributosElemento($jubilacionElement, $this->cNomina->Percepciones->JubilacionPensionRetiro);
            $percepciones->appendChild($jubilacionElement);
        }
        
        if (isset($this->cNomina->Percepciones->SeparacionIndemnizacion)) {
            $separacionElement = $xml->createElement("nomina12:SeparacionIndemnizacion");
            $this->AgregarAtributosElemento($separacionElement, $this->cNomina->Percepciones->SeparacionIndemnizacion);
            $percepciones->appendChild($separacionElement);
        }
        
        $root->appendChild($percepciones);
    }

    private function CrearDeduccionesXML($xml, $root)
    {
        $deducciones = $xml->createElement("nomina12:Deducciones");
        $this->AgregarAtributosElemento($deducciones, $this->cNomina->Deducciones);
        
        foreach ($this->cNomina->Deducciones->Deduccion as $deduccion) {
            $deduccionElement = $xml->createElement("nomina12:Deduccion");
            $this->AgregarAtributosElemento($deduccionElement, $deduccion);
            $deducciones->appendChild($deduccionElement);
        }
        
        $root->appendChild($deducciones);
    }

    private function CrearOtrosPagosXML($xml, $root)
    {
        $otrosPagos = $xml->createElement("nomina12:OtrosPagos");
        
        foreach ($this->cNomina->OtrosPagos->OtroPago as $otroPago) {
            $otroPagoElement = $xml->createElement("nomina12:OtroPago");
            $this->AgregarAtributosElemento($otroPagoElement, $otroPago);
            
            if (isset($otroPago->SubsidioAlEmpleo)) {
                $subsidioElement = $xml->createElement("nomina12:SubsidioAlEmpleo");
                $this->AgregarAtributosElemento($subsidioElement, $otroPago->SubsidioAlEmpleo);
                $otroPagoElement->appendChild($subsidioElement);
            }
            
            if (isset($otroPago->CompensacionSaldosAFavor)) {
                $compensacionElement = $xml->createElement("nomina12:CompensacionSaldosAFavor");
                $this->AgregarAtributosElemento($compensacionElement, $otroPago->CompensacionSaldosAFavor);
                $otroPagoElement->appendChild($compensacionElement);
            }
            
            $otrosPagos->appendChild($otroPagoElement);
        }
        
        $root->appendChild($otrosPagos);
    }

    private function CrearIncapacidadesXML($xml, $root)
    {
        $incapacidades = $xml->createElement("nomina12:Incapacidades");
        
        foreach ($this->cNomina->Incapacidades->Incapacidad as $incapacidad) {
            $incapacidadElement = $xml->createElement("nomina12:Incapacidad");
            $this->AgregarAtributosElemento($incapacidadElement, $incapacidad);
            $incapacidades->appendChild($incapacidadElement);
        }
        
        $root->appendChild($incapacidades);
    }

    /**
     * Crea el XML de Nómina con validaciones de Finkok
     * 
     * @param string $FinkokUser Usuario de Finkok
     * @param string $FinkokPass Contraseña de Finkok
     * @param string &$Errores Variable para almacenar errores
     * @param string $Ruta Ruta donde guardar el XML (opcional)
     * @param string $nameXML Nombre del archivo XML (opcional)
     * @param string &$ErrorE Variable para almacenar errores adicionales (opcional)
     * @return bool|string Retorna el XML como string si es exitoso, false si hay errores
     */
    public function CrearNominaXML($FinkokUser, $FinkokPass, &$Errores, $Ruta = null, $nameXML = null, &$ErrorE = null)
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
            $nombreXML = $nameXML ? strtoupper(str_replace(".XML", "", $nameXML)) : "NOMINA";
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
            $root->setAttribute("xmlns:nomina12", "http://www.sat.gob.mx/nomina12");
            $schemaLocation = "http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd http://www.sat.gob.mx/nomina12 http://www.sat.gob.mx/sitio_internet/cfd/nomina/nomina12.xsd";
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

            // Add Complemento
            $complemento = $xml->createElement("cfdi:Complemento");
            
            // Add Nómina
            $nomina = $xml->createElement("nomina12:Nomina");
            $nomina->setAttribute("Version", $this->cNomina->Version);
            $nomina->setAttribute("TipoNomina", $this->cNomina->TipoNomina);
            $nomina->setAttribute("FechaPago", $this->cNomina->FechaPago);
            $nomina->setAttribute("FechaInicialPago", $this->cNomina->FechaInicialPago);
            $nomina->setAttribute("FechaFinalPago", $this->cNomina->FechaFinalPago);
            $nomina->setAttribute("NumDiasPagados", $this->cNomina->NumDiasPagados);
            
            if (isset($this->cNomina->TotalPercepciones)) {
                $nomina->setAttribute("TotalPercepciones", $this->cNomina->TotalPercepciones);
            }
            if (isset($this->cNomina->TotalDeducciones)) {
                $nomina->setAttribute("TotalDeducciones", $this->cNomina->TotalDeducciones);
            }
            if (isset($this->cNomina->TotalOtrosPagos)) {
                $nomina->setAttribute("TotalOtrosPagos", $this->cNomina->TotalOtrosPagos);
            }

            // Add Emisor Nómina
            if (isset($this->cNomina->Emisor)) {
                $this->CrearEmisorNominaXML($xml, $nomina);
            }

            // Add Receptor Nómina
            if (isset($this->cNomina->Receptor)) {
                $this->CrearReceptorNominaXML($xml, $nomina);
            }

            // Add Percepciones
            if (isset($this->cNomina->Percepciones)) {
                $this->CrearPercepcionesXML($xml, $nomina);
            }

            // Add Deducciones
            if (isset($this->cNomina->Deducciones)) {
                $this->CrearDeduccionesXML($xml, $nomina);
            }

            // Add Otros Pagos
            if (isset($this->cNomina->OtrosPagos)) {
                $this->CrearOtrosPagosXML($xml, $nomina);
            }

            // Add Incapacidades
            if (isset($this->cNomina->Incapacidades)) {
                $this->CrearIncapacidadesXML($xml, $nomina);
            }

            $complemento->appendChild($nomina);
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
