<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AlfredoMejia\CfdiPhp\CFDIFactory;

echo "🚀 Ejemplo de Complemento de Nómina 1.2\n";
echo "========================================\n\n";

// 1️⃣ Validar credenciales de Finkok
echo "1️⃣ Validando credenciales...\n";

$finkokUser = 'usuario_finkok_demo';
$finkokPass = 'pass_finkok_demo';

$factory = new CFDIFactory();

$credentialsValidation = $factory->validateCredentials($finkokUser, $finkokPass);
if (!$credentialsValidation->isValid()) {
    echo "❌ Credenciales inválidas: " . implode(', ', $credentialsValidation->getErrors()) . "\n";
    exit(1);
}
echo "✅ Credenciales válidas\n\n";

// 2️⃣ Crear CFDI con complemento de Nómina
echo "2️⃣ Creando CFDI con complemento de Nómina...\n";

$cfdi = $factory->createNomina()
    ->comprobante(
        noCertificado: '30001000000500003416',
        serie: 'N',
        folio: '00001',
        fecha: '2025-08-20T12:00:00',
        subTotal: '1000.00',
        total: '1000.00',
        lugarExpedicion: '06500'
    )
    ->emisor(
        rfc: $finkokUser,
        nombre: 'EMPRESA RECURSOS HUMANOS SA DE CV',
        regimenFiscal: '601'
    )
    ->receptor(
        rfc: 'XEXX010101000',
        nombre: 'EMPLEADO EJEMPLO',
        regimenFiscalReceptor: '605',
        usoCFDI: 'CN01',
        domicilioFiscalReceptor: '06500'
    )
    ->concepto(
        claveProdServ: '84111506',
        cantidad: '1.00',
        claveUnidad: 'E48',
        descripcion: 'Pago de nómina',
        valorUnitario: '1000.00',
        importe: '1000.00',
        objetoImp: '01'
    )
    ->tipoNomina('O')
    ->fechasPago(
        fechaPago: '2025-08-20',
        fechaInicialPago: '2025-08-01',
        fechaFinalPago: '2025-08-15',
        numDiasPagados: '15'
    )
    ->emisorNomina([
        'curp' => 'XEXX010101HDFXXXA8',
        'registroPatronal' => 'O1234567890'
    ])
    ->receptorNomina([
        'curp' => 'XEXX010101HDFXXXA8',
        'numSeguridadSocial' => '12345678901',
        'fechaInicioRelLaboral' => '2020-01-01',
        'antiguedad' => 'P5Y',
        'tipoContrato' => '01',
        'sindicalizado' => 'No',
        'tipoJornada' => '01',
        'tipoRegimen' => '02',
        'numEmpleado' => '001',
        'departamento' => 'ADMINISTRATIVO',
        'puesto' => 'AUXILIAR',
        'riesgoPuesto' => '1',
        'periodicidadPago' => '04',
        'banco' => '012',
        'cuentaBancaria' => '012345678901234567',
        'salarioBaseCotApor' => '1000.00',
        'salarioDiarioIntegrado' => '1000.00'
    ])
    ->percepciones([
        'totalSueldos' => '1000.00',
        'totalSeparacionIndemnizacion' => '0.00',
        'totalJubilacionPensionRetiro' => '0.00',
        'totalGravado' => '1000.00',
        'totalExento' => '0.00'
    ])
    ->deducciones([
        'totalOtrasDeducciones' => '0.00',
        'totalImpuestosRetenidos' => '0.00'
    ])
    ->build();

echo "✅ CFDI con Nómina construido\n\n";

// 3️⃣ Procesar CFDI
echo "3️⃣ Procesando CFDI...\n";

$result = $factory->processCFDI(
    cfdiData: $cfdi,
    finkokUser: $finkokUser,
    finkokPassword: $finkokPass,
    filePath: __DIR__ . '/output/',
    fileName: 'CFDI_NOMINA'
);

if ($result->isSuccess()) {
    echo "✅ CFDI con Nómina procesado exitosamente\n";
    echo "📁 Archivo generado: " . $result->getFilePath() . "\n";
} else {
    echo "❌ Error al procesar CFDI: " . $result->getMessage() . "\n";
    if ($result->getErrors()) {
        echo "🔍 Errores: " . implode(', ', $result->getErrors()) . "\n";
    }
}

echo "\n🎉 Ejemplo completado!\n";
echo "\n📚 Este ejemplo demuestra:\n";
echo "  ✅ Creación de CFDI con complemento de Nómina 1.2\n";
echo "  ✅ Configuración de fechas de pago y tipo de nómina\n";
echo "  ✅ Configuración de emisor y receptor de nómina\n";
echo "  ✅ Configuración de percepciones y deducciones\n";
echo "  ✅ Procesamiento completo con Finkok\n";
echo "  ✅ Generación y guardado de XML\n";
