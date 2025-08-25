<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AlfredoMejia\CfdiPhp\CFDIFactory;

echo "🚀 Ejemplo de CFDI Básico 4.0\n";
echo "==============================\n\n";

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

// 2️⃣ Crear CFDI básico
echo "2️⃣ Creando CFDI básico...\n";

$cfdi = $factory->createCFDI40Builder()
    ->comprobante(
        noCertificado: '30001000000500003416',
        serie: 'A',
        folio: '00001',
        fecha: '2025-08-20T12:00:00',
        subTotal: '1000.00',
        total: '1160.00',
        lugarExpedicion: '06500'
    )
    ->emisor(
        rfc: 'EKU9003173C9',
        nombre: 'EMPRESA EJEMPLO SA DE CV',
        regimenFiscal: '601'
    )
    ->receptor(
        rfc: 'EKU9003173C9',
        nombre: 'CLIENTE EJEMPLO SA DE CV',
        regimenFiscalReceptor: '601',
        usoCFDI: 'G01',
        domicilioFiscalReceptor: '06500'
    )
    ->concepto(
        claveProdServ: '84111506',
        cantidad: '1.00',
        claveUnidad: 'H87',
        descripcion: 'Servicio de consultoría en programación',
        valorUnitario: '1000.00',
        importe: '1000.00',
        objetoImp: '01'
    )
    ->impuestosTotales(
        totalImpuestosRetenidos: '0.00',
        totalImpuestosTrasladados: '160.00'
    )
    ->trasladoTotal(
        base: '1000.00',
        impuesto: '002',
        tipoFactor: 'Tasa',
        tasaOCuota: '0.160000',
        importe: '160.00'
    )
    ->build();

echo "✅ CFDI básico construido\n\n";

// 3️⃣ Procesar CFDI
echo "3️⃣ Procesando CFDI...\n";

$result = $factory->processCFDI(
    cfdiData: $cfdi,
    finkokUser: $finkokUser,
    finkokPassword: $finkokPass,
    filePath: __DIR__ . '/output/',
    fileName: 'CFDI_BASICO'
);

if ($result->isSuccess()) {
    echo "✅ CFDI procesado exitosamente\n";
    echo "📁 Archivo generado: " . $result->getFilePath() . "\n";
} else {
    echo "❌ Error al procesar CFDI: " . $result->getMessage() . "\n";
    if ($result->getErrors()) {
        echo "🔍 Errores: " . implode(', ', $result->getErrors()) . "\n";
    }
}

echo "\n🎉 Ejemplo completado!\n";
echo "\n📚 Este ejemplo demuestra:\n";
echo "  ✅ Creación de CFDI 4.0 básico\n";
echo "  ✅ Configuración de comprobante, emisor y receptor\n";
echo "  ✅ Agregado de conceptos\n";
echo "  ✅ Configuración de impuestos (IVA)\n";
echo "  ✅ Procesamiento completo con Finkok\n";
echo "  ✅ Generación y guardado de XML\n";
