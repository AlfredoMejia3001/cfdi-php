<?php

class RecepcionPagos
{
    private $cComprobante;
    private $cEmisor;
    private $cReceptor;
    private $cConceptos = [];
    private $cComplemento;
    private $cPagos;
    private $aPagos = [];
    private $aDoctosRelacionados = [];
    private $aImpuestosDR = [];
    private $aImpuestosP = [];

    public function __construct()
    {
        $this->cComprobante = new stdClass();
        $this->cEmisor = new stdClass();
        $this->cReceptor = new stdClass();
        $this->cComplemento = new stdClass();
        $this->cPagos = new stdClass();
    }

    public function Schemma()
    {
        return array(
            "CFDI40" => "http://www.sat.gob.mx/esquemas/cfd/4 http://www.sat.gob.mx/esquemas/cfd/4/cfdv40.xsd",
            "PAGOS20" => "http://www.sat.gob.mx/Pagos20 http://www.sat.gob.mx/sitio_internet/cfd/Pagos/Pagos20.xsd"
        );
    }

    public function CFDI40($NoCertificado, $Serie, $Folio, $Fecha, $SubTotal, $Moneda, $Total, $TipoDeComprobante, $Exportacion, $LugarExpedicion)
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
        $this->cComprobante->SubTotal = $SubTotal;
        $this->cComprobante->Moneda = $Moneda;
        $this->cComprobante->Total = $Total;
        $this->cComprobante->TipoDeComprobante = $TipoDeComprobante;
        $this->cComprobante->Exportacion = $Exportacion;
        $this->cComprobante->LugarExpedicion = $LugarExpedicion;
    }

    public function AgregarEmisor($Rfc, $NombreE, $RegimenFiscalE)
    {
        $this->cEmisor->Rfc = $Rfc;
        $this->cEmisor->NombreE = $NombreE;
        $this->cEmisor->RegimenFiscalE = $RegimenFiscalE;
    }

    public function AgregarReceptor($RfcR, $NombreR, $RegimenFiscalReceptor, $UsoCFDI, $DomicilioFiscalReceptor)
    {
        $this->cReceptor->RfcR = $RfcR;
        $this->cReceptor->NombreR = $NombreR;
        $this->cReceptor->RegimenFiscalReceptor = $RegimenFiscalReceptor;
        $this->cReceptor->UsoCFDI = $UsoCFDI;
        $this->cReceptor->DomicilioFiscalReceptor = $DomicilioFiscalReceptor;
    }

    public function AgregarConcepto($ClaveProdServ, $Cantidad, $ClaveUnidad, $Descripcion, $ValorUnitario, $Importe, $ObjetoImp)
    {
        $concepto = new stdClass();
        $concepto->ClaveProdServ = $ClaveProdServ;
        $concepto->Cantidad = $Cantidad;
        $concepto->ClaveUnidad = $ClaveUnidad;
        $concepto->Descripcion = $Descripcion;
        $concepto->ValorUnitario = $ValorUnitario;
        $concepto->Importe = $Importe;
        $concepto->ObjetoImp = $ObjetoImp;
        
        $this->cConceptos[] = $concepto;
    }

    public function AgregarComplementoPagos()
    {
        $this->cComplemento->Pagos = new stdClass();
        $this->cComplemento->Pagos->Version = "2.0";
        $this->cComplemento->Pagos->Totales = new stdClass();
    }

    public function AgregarTotales($TotalRetencionesIVA, $TotalRetencionesISR, $TotalRetencionesIEPS, 
                                 $TotalTrasladosBaseIVA16, $TotalTrasladosImpuestoIVA16, 
                                 $TotalTrasladosBaseIVA8, $TotalTrasladosImpuestoIVA8, 
                                 $TotalTrasladosBaseIVA0, $TotalTrasladosImpuestoIVA0, 
                                 $TotalTrasladosBaseIVAExento, $MontoTotalPagos)
    {
        if (!empty(trim($TotalRetencionesIVA))) {
            $this->cComplemento->Pagos->Totales->TotalRetencionesIVA = $TotalRetencionesIVA;
        }
        if (!empty(trim($TotalRetencionesISR))) {
            $this->cComplemento->Pagos->Totales->TotalRetencionesISR = $TotalRetencionesISR;
        }
        if (!empty(trim($TotalRetencionesIEPS))) {
            $this->cComplemento->Pagos->Totales->TotalRetencionesIEPS = $TotalRetencionesIEPS;
        }
        if (!empty(trim($TotalTrasladosBaseIVA16))) {
            $this->cComplemento->Pagos->Totales->TotalTrasladosBaseIVA16 = $TotalTrasladosBaseIVA16;
        }
        if (!empty(trim($TotalTrasladosImpuestoIVA16))) {
            $this->cComplemento->Pagos->Totales->TotalTrasladosImpuestoIVA16 = $TotalTrasladosImpuestoIVA16;
        }
        if (!empty(trim($TotalTrasladosBaseIVA8))) {
            $this->cComplemento->Pagos->Totales->TotalTrasladosBaseIVA8 = $TotalTrasladosBaseIVA8;
        }
        if (!empty(trim($TotalTrasladosImpuestoIVA8))) {
            $this->cComplemento->Pagos->Totales->TotalTrasladosImpuestoIVA8 = $TotalTrasladosImpuestoIVA8;
        }
        if (!empty(trim($TotalTrasladosBaseIVA0))) {
            $this->cComplemento->Pagos->Totales->TotalTrasladosBaseIVA0 = $TotalTrasladosBaseIVA0;
        }
        if (!empty(trim($TotalTrasladosImpuestoIVA0))) {
            $this->cComplemento->Pagos->Totales->TotalTrasladosImpuestoIVA0 = $TotalTrasladosImpuestoIVA0;
        }
        if (!empty(trim($TotalTrasladosBaseIVAExento))) {
            $this->cComplemento->Pagos->Totales->TotalTrasladosBaseIVAExento = $TotalTrasladosBaseIVAExento;
        }

        $this->cComplemento->Pagos->Totales->MontoTotalPagos = $MontoTotalPagos;
    }

    public function AgregarPago($FechaPago, $FormaDePagoP, $MonedaP, $TipoCambioP, $Monto, 
                               $NumOperacion, $RfcEmisorCtaOrd, $NomBancoOrdExt, $CtaOrdenante, 
                               $RfcEmisorCtaBen, $CtaBeneficiario, $TipoCadPago, $CertPago, 
                               $CadPago, $SelloPago)
    {
        $pago = new stdClass();
        $pago->FechaPago = $FechaPago;
        $pago->FormaDePagoP = $FormaDePagoP;
        $pago->MonedaP = $MonedaP;
        if (!empty(trim($TipoCambioP))) {
            $pago->TipoCambioP = $TipoCambioP;
        }
        $pago->Monto = $Monto;
        if (!empty(trim($NumOperacion))) {
            $pago->NumOperacion = $NumOperacion;
        }
        if (!empty(trim($RfcEmisorCtaOrd))) {
            $pago->RfcEmisorCtaOrd = $RfcEmisorCtaOrd;
        }
        if (!empty(trim($NomBancoOrdExt))) {
            $pago->NomBancoOrdExt = $NomBancoOrdExt;
        }
        if (!empty(trim($CtaOrdenante))) {
            $pago->CtaOrdenante = $CtaOrdenante;
        }
        if (!empty(trim($RfcEmisorCtaBen))) {
            $pago->RfcEmisorCtaBen = $RfcEmisorCtaBen;
        }
        if (!empty(trim($CtaBeneficiario))) {
            $pago->CtaBeneficiario = $CtaBeneficiario;
        }
        if (!empty(trim($TipoCadPago))) {
            $pago->TipoCadPago = $TipoCadPago;
        }
        if (!empty(trim($CertPago))) {
            $pago->CertPago = $CertPago;
        }
        if (!empty(trim($CadPago))) {
            $pago->CadPago = $CadPago;
        }
        if (!empty(trim($SelloPago))) {
            $pago->SelloPago = $SelloPago;
        }
        $pago->DoctoRelacionado = [];
        $pago->ImpuestosP = new stdClass();
        
        $this->aPagos[] = $pago;
        return $pago;
    }

    public function AgregarDoctoRelacionado($pago, $IdDocumento, $Serie, $Folio, $MonedaDR, 
                                          $EquivalenciaDR, $NumParcialidad, $ImpSaldoAnt, 
                                          $ImpPagado, $ImpSaldoInsoluto, $ObjetoImpDR)
    {
        $docto = new stdClass();
        $docto->IdDocumento = $IdDocumento;
        if (!empty(trim($Serie))) {
            $docto->Serie = $Serie;
        }
        if (!empty(trim($Folio))) {
            $docto->Folio = $Folio;
        }
        $docto->MonedaDR = $MonedaDR;

        if (!empty(trim($EquivalenciaDR))) {
            $docto->EquivalenciaDR = $EquivalenciaDR;
        }
        $docto->NumParcialidad = $NumParcialidad;
        $docto->ImpSaldoAnt = $ImpSaldoAnt;
        $docto->ImpPagado = $ImpPagado;
        $docto->ImpSaldoInsoluto = $ImpSaldoInsoluto;
        $docto->ObjetoImpDR = $ObjetoImpDR;
        $docto->ImpuestosDR = new stdClass();
        
        $pago->DoctoRelacionado[] = $docto;
        return $docto;
    }

    public function AgregarRetencionDR($docto, $BaseDR, $ImpuestoDR, $TipoFactorDR, $TasaOCuotaDR, $ImporteDR)
    {
        if (!isset($docto->ImpuestosDR->RetencionesDR)) {
            $docto->ImpuestosDR->RetencionesDR = [];
        }
        
        $retencion = new stdClass();
        $retencion->BaseDR = $BaseDR;
        $retencion->ImpuestoDR = $ImpuestoDR;
        $retencion->TipoFactorDR = $TipoFactorDR;
        $retencion->TasaOCuotaDR = $TasaOCuotaDR;
        $retencion->ImporteDR = $ImporteDR;
        
        $docto->ImpuestosDR->RetencionesDR[] = $retencion;
    }

    public function AgregarTrasladoDR($docto, $BaseDR, $ImpuestoDR, $TipoFactorDR, $TasaOCuotaDR, $ImporteDR)
    {
        if (!isset($docto->ImpuestosDR->TrasladosDR)) {
            $docto->ImpuestosDR->TrasladosDR = [];
        }
        
        $traslado = new stdClass();
        $traslado->BaseDR = $BaseDR;
        $traslado->ImpuestoDR = $ImpuestoDR;
        $traslado->TipoFactorDR = $TipoFactorDR;
        if (!empty(trim($TasaOCuotaDR))) {
            $traslado->TasaOCuotaDR = $TasaOCuotaDR;
        }
        if (!empty(trim($ImporteDR))) {
            $traslado->ImporteDR = $ImporteDR;
        }
        
        $docto->ImpuestosDR->TrasladosDR[] = $traslado;
    }

    public function AgregarRetencionP($pago, $ImpuestoP, $ImporteP)
    {
        if (!isset($pago->ImpuestosP->RetencionesP)) {
            $pago->ImpuestosP->RetencionesP = [];
        }
        
        $retencion = new stdClass();
        $retencion->ImpuestoP = $ImpuestoP;
        $retencion->ImporteP = $ImporteP;
        
        $pago->ImpuestosP->RetencionesP[] = $retencion;
    }

    public function AgregarTrasladoP($pago, $BaseP, $ImpuestoP, $TipoFactorP, $TasaOCuotaP, $ImporteP)
    {
        if (!isset($pago->ImpuestosP->TrasladosP)) {
            $pago->ImpuestosP->TrasladosP = [];
        }
        
        $traslado = new stdClass();
        $traslado->BaseP = $BaseP;
        $traslado->ImpuestoP = $ImpuestoP;
        $traslado->TipoFactorP = $TipoFactorP;
        if (!empty(trim($TasaOCuotaP))) {
            $traslado->TasaOCuotaP = $TasaOCuotaP;
        }
        if (!empty(trim($ImporteP))) {
            $traslado->ImporteP = $ImporteP;
        }
        
        $pago->ImpuestosP->TrasladosP[] = $traslado;
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
        try {
            // Configuración de rutas
            $nombreXML = $nameXML ? strtoupper(str_replace(".XML", "", $nameXML)) : "RECEPCION_PAGOS";
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
            $root->setAttribute("xmlns:pago20", "http://www.sat.gob.mx/Pagos20");
            $schemaLocation = "http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd http://www.sat.gob.mx/Pagos20 http://www.sat.gob.mx/sitio_internet/cfd/Pagos/Pagos20.xsd";
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
            $root->setAttribute("SubTotal", $this->cComprobante->SubTotal);
            $root->setAttribute("Moneda", $this->cComprobante->Moneda);
            $root->setAttribute("Total", $this->cComprobante->Total);
            $root->setAttribute("TipoDeComprobante", $this->cComprobante->TipoDeComprobante);
            $root->setAttribute("Exportacion", $this->cComprobante->Exportacion);
            $root->setAttribute("LugarExpedicion", $this->cComprobante->LugarExpedicion);
            $xml->appendChild($root);

            // Add Emisor
            $emisor = $xml->createElement("cfdi:Emisor");
            $emisor->setAttribute("Rfc", $this->cEmisor->Rfc);
            $emisor->setAttribute("Nombre", $this->cEmisor->NombreE);
            $emisor->setAttribute("RegimenFiscal", $this->cEmisor->RegimenFiscalE);
            $root->appendChild($emisor);

            // Add Receptor
            $receptor = $xml->createElement("cfdi:Receptor");
            $receptor->setAttribute("Rfc", $this->cReceptor->RfcR);
            $receptor->setAttribute("Nombre", $this->cReceptor->NombreR);
            $receptor->setAttribute("UsoCFDI", $this->cReceptor->UsoCFDI);
            $receptor->setAttribute("DomicilioFiscalReceptor", $this->cReceptor->DomicilioFiscalReceptor);
            $receptor->setAttribute("RegimenFiscalReceptor", $this->cReceptor->RegimenFiscalReceptor);
            $root->appendChild($receptor);

            // Add Conceptos
            $conceptos = $xml->createElement("cfdi:Conceptos");
            foreach ($this->cConceptos as $concepto) {
                $conceptoElement = $xml->createElement("cfdi:Concepto");
                $conceptoElement->setAttribute("ClaveProdServ", $concepto->ClaveProdServ);
                $conceptoElement->setAttribute("Cantidad", $concepto->Cantidad);
                $conceptoElement->setAttribute("ClaveUnidad", $concepto->ClaveUnidad);
                $conceptoElement->setAttribute("Descripcion", $concepto->Descripcion);
                $conceptoElement->setAttribute("ValorUnitario", $concepto->ValorUnitario);
                $conceptoElement->setAttribute("Importe", $concepto->Importe);
                $conceptoElement->setAttribute("ObjetoImp", $concepto->ObjetoImp);
                $conceptos->appendChild($conceptoElement);
            }
            $root->appendChild($conceptos);

            // Add Complemento
            $complemento = $xml->createElement("cfdi:Complemento");
            
            // Add Pagos
            $pagos = $xml->createElement("pago20:Pagos");
            $pagos->setAttribute("Version", $this->cComplemento->Pagos->Version);
            
            // Add Totales
            $totales = $xml->createElement("pago20:Totales");
            if (isset($this->cComplemento->Pagos->Totales->TotalRetencionesIVA) && !empty(trim($this->cComplemento->Pagos->Totales->TotalRetencionesIVA))) {
                $totales->setAttribute("TotalRetencionesIVA", $this->cComplemento->Pagos->Totales->TotalRetencionesIVA);
            }
            if (isset($this->cComplemento->Pagos->Totales->TotalRetencionesISR) && !empty(trim($this->cComplemento->Pagos->Totales->TotalRetencionesISR))) {
                $totales->setAttribute("TotalRetencionesISR", $this->cComplemento->Pagos->Totales->TotalRetencionesISR);
            }
            if (isset($this->cComplemento->Pagos->Totales->TotalRetencionesIEPS) && !empty(trim($this->cComplemento->Pagos->Totales->TotalRetencionesIEPS))) {
                $totales->setAttribute("TotalRetencionesIEPS", $this->cComplemento->Pagos->Totales->TotalRetencionesIEPS);
            }
            if (isset($this->cComplemento->Pagos->Totales->TotalTrasladosBaseIVA16) && !empty(trim($this->cComplemento->Pagos->Totales->TotalTrasladosBaseIVA16))) {
                $totales->setAttribute("TotalTrasladosBaseIVA16", $this->cComplemento->Pagos->Totales->TotalTrasladosBaseIVA16);
            }
            if (isset($this->cComplemento->Pagos->Totales->TotalTrasladosImpuestoIVA16) && !empty(trim($this->cComplemento->Pagos->Totales->TotalTrasladosImpuestoIVA16))) {
                $totales->setAttribute("TotalTrasladosImpuestoIVA16", $this->cComplemento->Pagos->Totales->TotalTrasladosImpuestoIVA16);
            }
            if (isset($this->cComplemento->Pagos->Totales->TotalTrasladosBaseIVA8) && !empty(trim($this->cComplemento->Pagos->Totales->TotalTrasladosBaseIVA8))) {
                $totales->setAttribute("TotalTrasladosBaseIVA8", $this->cComplemento->Pagos->Totales->TotalTrasladosBaseIVA8);
            }
            if (isset($this->cComplemento->Pagos->Totales->TotalTrasladosImpuestoIVA8) && !empty(trim($this->cComplemento->Pagos->Totales->TotalTrasladosImpuestoIVA8))) {
                $totales->setAttribute("TotalTrasladosImpuestoIVA8", $this->cComplemento->Pagos->Totales->TotalTrasladosImpuestoIVA8);
            }
            if (isset($this->cComplemento->Pagos->Totales->TotalTrasladosBaseIVA0) && !empty(trim($this->cComplemento->Pagos->Totales->TotalTrasladosBaseIVA0))) {
                $totales->setAttribute("TotalTrasladosBaseIVA0", $this->cComplemento->Pagos->Totales->TotalTrasladosBaseIVA0);
            }
            if (isset($this->cComplemento->Pagos->Totales->TotalTrasladosImpuestoIVA0) && !empty(trim($this->cComplemento->Pagos->Totales->TotalTrasladosImpuestoIVA0))) {
                $totales->setAttribute("TotalTrasladosImpuestoIVA0", $this->cComplemento->Pagos->Totales->TotalTrasladosImpuestoIVA0);
            }
            if (isset($this->cComplemento->Pagos->Totales->TotalTrasladosBaseIVAExento) && !empty(trim($this->cComplemento->Pagos->Totales->TotalTrasladosBaseIVAExento))) {
                $totales->setAttribute("TotalTrasladosBaseIVAExento", $this->cComplemento->Pagos->Totales->TotalTrasladosBaseIVAExento);
            }
            $totales->setAttribute("MontoTotalPagos", $this->cComplemento->Pagos->Totales->MontoTotalPagos);
            $pagos->appendChild($totales);

            // Add each Pago
            foreach ($this->aPagos as $pago) {
                $pagoElement = $xml->createElement("pago20:Pago");
                $pagoElement->setAttribute("FechaPago", $pago->FechaPago);
                $pagoElement->setAttribute("FormaDePagoP", $pago->FormaDePagoP);
                $pagoElement->setAttribute("MonedaP", $pago->MonedaP);
                if (!empty(trim($pago->TipoCambioP))) {
                    $pagoElement->setAttribute("TipoCambioP", $pago->TipoCambioP);
                }
                $pagoElement->setAttribute("Monto", $pago->Monto);
                if (isset($pago->NumOperacion) && !empty(trim($pago->NumOperacion))) {
                    $pagoElement->setAttribute("NumOperacion", $pago->NumOperacion);
                }
                if (!empty(trim($pago->RfcEmisorCtaOrd))) {
                    $pagoElement->setAttribute("RfcEmisorCtaOrd", $pago->RfcEmisorCtaOrd);
                }
                if (!empty(trim($pago->NomBancoOrdExt))) {
                    $pagoElement->setAttribute("NomBancoOrdExt", $pago->NomBancoOrdExt);
                }
                if (!empty(trim($pago->CtaOrdenante))) {
                    $pagoElement->setAttribute("CtaOrdenante", $pago->CtaOrdenante);
                }
                if (!empty(trim($pago->RfcEmisorCtaBen))) {
                    $pagoElement->setAttribute("RfcEmisorCtaBen", $pago->RfcEmisorCtaBen);
                }
                if (!empty(trim($pago->CtaBeneficiario))) {
                    $pagoElement->setAttribute("CtaBeneficiario", $pago->CtaBeneficiario);
                }
                if (!empty(trim($pago->TipoCadPago))) {
                    $pagoElement->setAttribute("TipoCadPago", $pago->TipoCadPago);
                }
                if (isset($pago->CertPago) && !empty(trim($pago->CertPago))) {
                    $pagoElement->setAttribute("CertPago", $pago->CertPago);
                }
                if (!empty(trim($pago->CadPago))) {
                    $pagoElement->setAttribute("CadPago", $pago->CadPago);
                }
                if (isset($pago->SelloPago) && !empty(trim($pago->SelloPago))) {
                    $pagoElement->setAttribute("SelloPago", $pago->SelloPago);
                }

                // Add DoctoRelacionado for each pago
                foreach ($pago->DoctoRelacionado as $docto) {
                    $doctoElement = $xml->createElement("pago20:DoctoRelacionado");
                    $doctoElement->setAttribute("IdDocumento", $docto->IdDocumento);
                    if (!empty(trim($docto->Serie))) {
                        $doctoElement->setAttribute("Serie", $docto->Serie);
                    }
                    if (!empty(trim($docto->Folio))) {
                        $doctoElement->setAttribute("Folio", $docto->Folio);
                    }
                   
                    $doctoElement->setAttribute("MonedaDR", $docto->MonedaDR);
                    
                    if (!empty(trim($docto->EquivalenciaDR))) {
                        $doctoElement->setAttribute("EquivalenciaDR", $docto->EquivalenciaDR);
                    }
                    $doctoElement->setAttribute("NumParcialidad", $docto->NumParcialidad);
                    $doctoElement->setAttribute("ImpSaldoAnt", $docto->ImpSaldoAnt);
                    $doctoElement->setAttribute("ImpPagado", $docto->ImpPagado);
                    $doctoElement->setAttribute("ImpSaldoInsoluto", $docto->ImpSaldoInsoluto);
                    $doctoElement->setAttribute("ObjetoImpDR", $docto->ObjetoImpDR);

                    // Add ImpuestosDR
                    if (isset($docto->ImpuestosDR)) {
                        $impuestosDRElement = $xml->createElement("pago20:ImpuestosDR");
                        
                        // Add RetencionesDR
                        if (isset($docto->ImpuestosDR->RetencionesDR) && !empty($docto->ImpuestosDR->RetencionesDR)) {
                            $retencionesDRElement = $xml->createElement("pago20:RetencionesDR");
                            foreach ($docto->ImpuestosDR->RetencionesDR as $retencion) {
                                $retencionElement = $xml->createElement("pago20:RetencionDR");
                                $retencionElement->setAttribute("BaseDR", $retencion->BaseDR);
                                $retencionElement->setAttribute("ImpuestoDR", $retencion->ImpuestoDR);
                                $retencionElement->setAttribute("TipoFactorDR", $retencion->TipoFactorDR);
                                $retencionElement->setAttribute("TasaOCuotaDR", $retencion->TasaOCuotaDR);
                                $retencionElement->setAttribute("ImporteDR", $retencion->ImporteDR);
                                $retencionesDRElement->appendChild($retencionElement);
                            }
                            $impuestosDRElement->appendChild($retencionesDRElement);
                        }

                        // Add TrasladosDR
                        if (isset($docto->ImpuestosDR->TrasladosDR) && !empty($docto->ImpuestosDR->TrasladosDR)) {
                            $trasladosDRElement = $xml->createElement("pago20:TrasladosDR");
                            foreach ($docto->ImpuestosDR->TrasladosDR as $traslado) {
                                $trasladoElement = $xml->createElement("pago20:TrasladoDR");
                                $trasladoElement->setAttribute("BaseDR", $traslado->BaseDR);
                                $trasladoElement->setAttribute("ImpuestoDR", $traslado->ImpuestoDR);
                                $trasladoElement->setAttribute("TipoFactorDR", $traslado->TipoFactorDR);
                                if (!empty(trim($traslado->TasaOCuotaDR))) {
                                    $trasladoElement->setAttribute("TasaOCuotaDR", $traslado->TasaOCuotaDR);
                                }
                                if (!empty(trim($traslado->ImporteDR))) {
                                    $trasladoElement->setAttribute("ImporteDR", $traslado->ImporteDR);
                                }
                                $trasladosDRElement->appendChild($trasladoElement);
                            }
                            $impuestosDRElement->appendChild($trasladosDRElement);
                        }

                        $doctoElement->appendChild($impuestosDRElement);
                    }

                    $pagoElement->appendChild($doctoElement);
                }

                // Add ImpuestosP
                if (isset($pago->ImpuestosP)) {
                    $impuestosPElement = $xml->createElement("pago20:ImpuestosP");
                    
                    // Add RetencionesP
                    if (isset($pago->ImpuestosP->RetencionesP) && !empty($pago->ImpuestosP->RetencionesP)) {
                        $retencionesPElement = $xml->createElement("pago20:RetencionesP");
                        foreach ($pago->ImpuestosP->RetencionesP as $retencion) {
                            $retencionElement = $xml->createElement("pago20:RetencionP");
                            $retencionElement->setAttribute("ImpuestoP", $retencion->ImpuestoP);
                            $retencionElement->setAttribute("ImporteP", $retencion->ImporteP);
                            $retencionesPElement->appendChild($retencionElement);
                        }
                        $impuestosPElement->appendChild($retencionesPElement);
                    }

                    // Add TrasladosP
                    if (isset($pago->ImpuestosP->TrasladosP) && !empty($pago->ImpuestosP->TrasladosP)) {
                        $trasladosPElement = $xml->createElement("pago20:TrasladosP");
                        foreach ($pago->ImpuestosP->TrasladosP as $traslado) {
                            $trasladoElement = $xml->createElement("pago20:TrasladoP");
                            $trasladoElement->setAttribute("BaseP", $traslado->BaseP);
                            $trasladoElement->setAttribute("ImpuestoP", $traslado->ImpuestoP);
                            $trasladoElement->setAttribute("TipoFactorP", $traslado->TipoFactorP);
                            if (!empty(trim($traslado->TasaOCuotaP))) {
                                $trasladoElement->setAttribute("TasaOCuotaP", $traslado->TasaOCuotaP);
                            }
                            if (!empty(trim($traslado->ImporteP))) {
                                $trasladoElement->setAttribute("ImporteP", $traslado->ImporteP);
                            }
                            $trasladosPElement->appendChild($trasladoElement);
                        }
                        $impuestosPElement->appendChild($trasladosPElement);
                    }

                    $pagoElement->appendChild($impuestosPElement);
                }

                $pagos->appendChild($pagoElement);
            }

            $complemento->appendChild($pagos);
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
