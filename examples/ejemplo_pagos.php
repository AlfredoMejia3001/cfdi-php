<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AlfredoMejia\CfdiPhp\CFDIFactory;

echo "🚀 Ejemplo de Complemento de Pagos 2.0\n";
echo "=======================================\n\n";

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

// 2️⃣ Crear CFDI con complemento de Pagos
echo "2️⃣ Creando CFDI con complemento de Pagos...\n";

$cfdi = $factory->createPagos()
    ->comprobante(
        noCertificado: '30001000000500003416',
        serie: 'P',
        folio: '00001',
        fecha: '2025-08-20T12:00:00',
        subTotal: '0.00',
        total: '0.00',
        lugarExpedicion: '20928',
        tipoDeComprobante: 'P'
    )
    ->emisor(
        rfc: 'EKU9003173C9',
        nombre: 'ESCUELA KEMPER URGATE',
        regimenFiscal: '601'
    )
    ->receptor(
        rfc: 'MASO451221PM4',
        nombre: 'MARIA OLIVIA MARTINEZ SAGAZ',
        regimenFiscalReceptor: '605',
        usoCFDI: 'CP01',
        domicilioFiscalReceptor: '80290'
    )
    ->concepto(
        claveProdServ: '84111506',
        cantidad: '1.00',
        claveUnidad: 'E48',
        descripcion: 'Pago por servicios',
        valorUnitario: '0.00',
        importe: '0.00',
        objetoImp: '01'
    )
    ->pago(
        fechaPago: '2025-08-20',
        formaDePagoP: '01',
        monto: '1000.00',
        monedaP: 'MXN',
        tipoCambioP: '1.00'
    )
    ->doctoRelacionado(
        idDocumento: 'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
        monedaDR: 'MXN',
        metodoDePagoDR: 'PPD',
        numParcialidad: '1',
        impSaldoAnt: '1000.00',
        impPagado: '1000.00',
        impSaldoInsoluto: '0.00'
    )
    ->trasladoDR(
        base: '1000.00',
        impuesto: '002',
        tipoFactor: 'Tasa',
        tasaOCuota: '0.160000',
        importe: '160.00'
    )
    ->impuestosTotalesDR(
        totalImpuestosRetenidos: '0.00',
        totalImpuestosTrasladados: '160.00'
    )
    ->trasladoTotalDR(
        base: '1000.00',
        impuesto: '002',
        tipoFactor: 'Tasa',
        tasaOCuota: '0.160000',
        importe: '160.00'
    )
    ->build();

echo "✅ CFDI con Pagos construido\n\n";

// 3️⃣ Procesar CFDI
echo "3️⃣ Procesando CFDI...\n";

$result = $factory->processCFDI(
    cfdiData: $cfdi,
    finkokUser: $finkokUser,
    finkokPassword: $finkokPass,
    filePath: __DIR__ . '/output/',
    fileName: 'CFDI_PAGOS'
);

if ($result->isSuccess()) {
    echo "✅ CFDI con Pagos procesado exitosamente\n";
    echo "📁 Archivo generado: " . $result->getFilePath() . "\n";
} else {
    echo "❌ Error al procesar CFDI: " . $result->getMessage() . "\n";
    if ($result->getErrors()) {
        echo "🔍 Errores: " . implode(', ', $result->getErrors()) . "\n";
    }
}

echo "\n🎉 Ejemplo completado!\n";
echo "\n📚 Este ejemplo demuestra:\n";
echo "  ✅ Creación de CFDI con complemento de Pagos 2.0\n";
echo "  ✅ Configuración de pagos individuales\n";
echo "  ✅ Documentos relacionados con impuestos\n";
echo "  ✅ Manejo de impuestos retenidos y trasladados\n";
echo "  ✅ Procesamiento completo con Finkok\n";
echo "  ✅ Generación y guardado de XML\n";
