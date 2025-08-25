<?php

declare(strict_types=1);

namespace AlfredoMejia\CfdiPhp\Strategies\Nomina;

use AlfredoMejia\CfdiPhp\Contracts\ComplementStrategyInterface;
use DOMDocument;
use DOMElement;

/**
 * Estrategia para el complemento de Nómina 1.2
 */
class NominaStrategy implements ComplementStrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function generateXML(DOMDocument $xml, DOMElement $complementoElement, object $complementData): void
    {
        $nominaElement = $xml->createElement('nomina12:Nomina');
        $nominaElement->setAttribute('Version', '1.2');
        $nominaElement->setAttribute('TipoNomina', $complementData->getTipoNomina());
        $nominaElement->setAttribute('FechaPago', $complementData->getFechaPago());
        $nominaElement->setAttribute('FechaInicialPago', $complementData->getFechaInicialPago());
        $nominaElement->setAttribute('FechaFinalPago', $complementData->getFechaFinalPago());
        $nominaElement->setAttribute('NumDiasPagados', $complementData->getNumDiasPagados());
        
        // Agregar emisor
        $this->addEmisor($xml, $nominaElement, $complementData);
        
        // Agregar receptor
        $this->addReceptor($xml, $nominaElement, $complementData);
        
        // Agregar percepciones
        $this->addPercepciones($xml, $nominaElement, $complementData);
        
        // Agregar deducciones
        $this->addDeducciones($xml, $nominaElement, $complementData);
        
        // Agregar otros pagos
        $this->addOtrosPagos($xml, $nominaElement, $complementData);
        
        // Agregar incapacidades
        $this->addIncapacidades($xml, $nominaElement, $complementData);
        
        $complementoElement->appendChild($nominaElement);
    }
    
    /**
     * Agrega el emisor de la nómina
     */
    private function addEmisor(DOMDocument $xml, DOMElement $nominaElement, object $complementData): void
    {
        $emisor = $complementData->getEmisor();
        if (empty($emisor)) {
            return;
        }
        
        $emisorElement = $xml->createElement('nomina12:Emisor');
        $emisorElement->setAttribute('Curp', $emisor['curp']);
        $emisorElement->setAttribute('RegistroPatronal', $emisor['registroPatronal']);
        
        if (isset($emisor['rfcPatronOrigen'])) {
            $emisorElement->setAttribute('RfcPatronOrigen', $emisor['rfcPatronOrigen']);
        }
        
        $nominaElement->appendChild($emisorElement);
    }
    
    /**
     * Agrega el receptor de la nómina
     */
    private function addReceptor(DOMDocument $xml, DOMElement $nominaElement, object $complementData): void
    {
        $receptor = $complementData->getReceptor();
        $receptorElement = $xml->createElement('nomina12:Receptor');
        $receptorElement->setAttribute('Curp', $receptor['curp']);
        $receptorElement->setAttribute('NumSeguridadSocial', $receptor['numSeguridadSocial']);
        $receptorElement->setAttribute('FechaInicioRelLaboral', $receptor['fechaInicioRelLaboral']);
        $receptorElement->setAttribute('Antiguedad', $receptor['antiguedad']);
        $receptorElement->setAttribute('TipoContrato', $receptor['tipoContrato']);
        $receptorElement->setAttribute('Sindicalizado', $receptor['sindicalizado']);
        $receptorElement->setAttribute('TipoJornada', $receptor['tipoJornada']);
        $receptorElement->setAttribute('TipoRegimen', $receptor['tipoRegimen']);
        $receptorElement->setAttribute('NumEmpleado', $receptor['numEmpleado']);
        $receptorElement->setAttribute('Departamento', $receptor['departamento']);
        $receptorElement->setAttribute('Puesto', $receptor['puesto']);
        $receptorElement->setAttribute('RiesgoPuesto', $receptor['riesgoPuesto']);
        $receptorElement->setAttribute('PeriodicidadPago', $receptor['periodicidadPago']);
        $receptorElement->setAttribute('Banco', $receptor['banco']);
        $receptorElement->setAttribute('CuentaBancaria', $receptor['cuentaBancaria']);
        $receptorElement->setAttribute('SalarioBaseCotApor', $receptor['salarioBaseCotApor']);
        $receptorElement->setAttribute('SalarioDiarioIntegrado', $receptor['salarioDiarioIntegrado']);
        
        $nominaElement->appendChild($receptorElement);
    }
    
    /**
     * Agrega las percepciones
     */
    private function addPercepciones(DOMDocument $xml, DOMElement $nominaElement, object $complementData): void
    {
        $percepciones = $complementData->getPercepciones();
        if (empty($percepciones)) {
            return;
        }
        
        $percepcionesElement = $xml->createElement('nomina12:Percepciones');
        $percepcionesElement->setAttribute('TotalSueldos', $percepciones['totalSueldos']);
        
        if (isset($percepciones['totalSeparacionIndemnizacion'])) {
            $percepcionesElement->setAttribute('TotalSeparacionIndemnizacion', $percepciones['totalSeparacionIndemnizacion']);
        }
        if (isset($percepciones['totalJubilacionPensionRetiro'])) {
            $percepcionesElement->setAttribute('TotalJubilacionPensionRetiro', $percepciones['totalJubilacionPensionRetiro']);
        }
        if (isset($percepciones['totalGravado'])) {
            $percepcionesElement->setAttribute('TotalGravado', $percepciones['totalGravado']);
        }
        if (isset($percepciones['totalExento'])) {
            $percepcionesElement->setAttribute('TotalExento', $percepciones['totalExento']);
        }
        
        // Agregar percepciones individuales
        if (isset($percepciones['percepciones']) && is_array($percepciones['percepciones'])) {
            foreach ($percepciones['percepciones'] as $percepcion) {
                $percepcionElement = $xml->createElement('nomina12:Percepcion');
                $percepcionElement->setAttribute('TipoPercepcion', $percepcion['tipoPercepcion']);
                $percepcionElement->setAttribute('Clave', $percepcion['clave']);
                $percepcionElement->setAttribute('Concepto', $percepcion['concepto']);
                $percepcionElement->setAttribute('ImporteGravado', $percepcion['importeGravado']);
                $percepcionElement->setAttribute('ImporteExento', $percepcion['importeExento']);
                
                $percepcionesElement->appendChild($percepcionElement);
            }
        }
        
        $nominaElement->appendChild($percepcionesElement);
    }
    
    /**
     * Agrega las deducciones
     */
    private function addDeducciones(DOMDocument $xml, DOMElement $nominaElement, object $complementData): void
    {
        $deducciones = $complementData->getDeducciones();
        if (empty($deducciones)) {
            return;
        }
        
        $deduccionesElement = $xml->createElement('nomina12:Deducciones');
        $deduccionesElement->setAttribute('TotalOtrasDeducciones', $deducciones['totalOtrasDeducciones']);
        $deduccionesElement->setAttribute('TotalImpuestosRetenidos', $deducciones['totalImpuestosRetenidos']);
        
        // Agregar deducciones individuales
        if (isset($deducciones['deducciones']) && is_array($deducciones['deducciones'])) {
            foreach ($deducciones['deducciones'] as $deduccion) {
                $deduccionElement = $xml->createElement('nomina12:Deduccion');
                $deduccionElement->setAttribute('TipoDeduccion', $deduccion['tipoDeduccion']);
                $deduccionElement->setAttribute('Clave', $deduccion['clave']);
                $deduccionElement->setAttribute('Concepto', $deduccion['concepto']);
                $deduccionElement->setAttribute('Importe', $deduccion['importe']);
                
                $deduccionesElement->appendChild($deduccionElement);
            }
        }
        
        $nominaElement->appendChild($deduccionesElement);
    }
    
    /**
     * Agrega otros pagos
     */
    private function addOtrosPagos(DOMDocument $xml, DOMElement $nominaElement, object $complementData): void
    {
        $otrosPagos = $complementData->getOtrosPagos();
        if (empty($otrosPagos)) {
            return;
        }
        
        $otrosPagosElement = $xml->createElement('nomina12:OtrosPagos');
        
        foreach ($otrosPagos as $otroPago) {
            $otroPagoElement = $xml->createElement('nomina12:OtroPago');
            $otroPagoElement->setAttribute('TipoOtroPago', $otroPago['tipoOtroPago']);
            $otroPagoElement->setAttribute('Clave', $otroPago['clave']);
            $otroPagoElement->setAttribute('Concepto', $otroPago['concepto']);
            $otroPagoElement->setAttribute('Importe', $otroPago['importe']);
            
            $otrosPagosElement->appendChild($otroPagoElement);
        }
        
        $nominaElement->appendChild($otrosPagosElement);
    }
    
    /**
     * Agrega incapacidades
     */
    private function addIncapacidades(DOMDocument $xml, DOMElement $nominaElement, object $complementData): void
    {
        $incapacidades = $complementData->getIncapacidades();
        if (empty($incapacidades)) {
            return;
        }
        
        $incapacidadesElement = $xml->createElement('nomina12:Incapacidades');
        
        foreach ($incapacidades as $incapacidad) {
            $incapacidadElement = $xml->createElement('nomina12:Incapacidad');
            $incapacidadElement->setAttribute('DiasIncapacidad', $incapacidad['diasIncapacidad']);
            $incapacidadElement->setAttribute('TipoIncapacidad', $incapacidad['tipoIncapacidad']);
            $incapacidadElement->setAttribute('Descuento', $incapacidad['descuento']);
            
            $incapacidadesElement->appendChild($incapacidadElement);
        }
        
        $nominaElement->appendChild($incapacidadesElement);
    }

    /**
     * {@inheritdoc}
     */
    public function validate(object $complementData): array
    {
        $errors = [];
        
        if (empty($complementData->getTipoNomina())) {
            $errors[] = 'El tipo de nómina es requerido';
        }
        
        if (empty($complementData->getFechaPago())) {
            $errors[] = 'La fecha de pago es requerida';
        }
        
        if (empty($complementData->getEmisor())) {
            $errors[] = 'La información del emisor es requerida';
        }
        
        if (empty($complementData->getReceptor())) {
            $errors[] = 'La información del receptor es requerida';
        }
        
        return $errors;
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace(): string
    {
        return 'http://www.sat.gob.mx/nomina12';
    }

    /**
     * {@inheritdoc}
     */
    public function getSchemaLocation(): string
    {
        return 'http://www.sat.gob.mx/nomina12 http://www.sat.gob.mx/sitio_internet/cfd/nomina/nomina12.xsd';
    }

    /**
     * {@inheritdoc}
     */
    public function getComplementName(): string
    {
        return 'nomina12';
    }
}
