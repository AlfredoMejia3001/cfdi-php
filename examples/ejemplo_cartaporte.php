<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AlfredoMejia\CfdiPhp\CFDIFactory;

echo "🚀 Ejemplo de Complemento de Carta Porte 3.1\n";
echo "=============================================\n\n";

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

// 2️⃣ Crear CFDI con complemento de Carta Porte
echo "2️⃣ Creando CFDI con complemento de Carta Porte...\n";

$cfdi = $factory->createCartaPorte()
    ->comprobante(
        noCertificado: '30001000000500003416',
        serie: 'CP',
        folio: '00001',
        fecha: '2025-08-20T12:00:00',
        subTotal: '1000.00',
        total: '1160.00',
        lugarExpedicion: '06500'
    )
    ->emisor(
        rfc: 'XEXX010101000',
        nombre: 'EMPRESA TRANSPORTE SA DE CV',
        regimenFiscal: '601'
    )
    ->receptor(
        rfc: 'XEXX010101000',
        nombre: 'CLIENTE TRANSPORTE SA DE CV',
        regimenFiscalReceptor: '601',
        usoCFDI: 'G01',
        domicilioFiscalReceptor: '06500'
    )
    ->concepto(
        claveProdServ: '78101800',
        cantidad: '1.00',
        claveUnidad: 'H87',
        descripcion: 'Servicio de transporte de mercancía',
        valorUnitario: '1000.00',
        importe: '1000.00',
        objetoImp: '02'
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
    ->transporteInternacional('No')
    ->origen(
        idUbicacion: 'ORIGEN001',
        rfcRemitente: $finkokUser,
        fechaHora: '2025-08-20T08:00:00',
        domicilio: [
            'estado' => 'CDMX',
            'pais' => 'MEX',
            'codigoPostal' => '06000',
            'calle' => 'Av. Principal',
            'numeroExterior' => '123',
            'colonia' => 'Centro',
            'municipio' => 'Cuauhtémoc'
        ]
    )
    ->destino(
        idUbicacion: 'DESTINO001',
        rfcDestinatario: 'XEXX010101000',
        fechaHora: '2025-08-20T18:00:00',
        domicilio: [
            'estado' => 'CDMX',
            'pais' => 'MEX',
            'codigoPostal' => '07000',
            'calle' => 'Calle Secundaria',
            'numeroExterior' => '456',
            'colonia' => 'Industrial',
            'municipio' => 'Gustavo A. Madero'
        ]
    )
    ->mercancia(
        bienesTransp: '10101501',
        descripcion: 'Productos manufacturados',
        cantidad: '1.00',
        claveUnidadPeso: 'KGM',
        pesoEnKg: '50.00'
    )
    ->totalesMercancias(
        pesoBrutoTotal: '50.00',
        unidadPeso: 'KGM',
        numTotalMercancias: '1'
    )
    ->operador('XEXX010101000', 'OPERADOR TRANSPORTE SA DE CV')
    ->build();

echo "✅ CFDI con Carta Porte construido\n\n";

// 3️⃣ Procesar CFDI
echo "3️⃣ Procesando CFDI...\n";

$result = $factory->processCFDI(
    cfdiData: $cfdi,
    finkokUser: $finkokUser,
    finkokPassword: $finkokPass,
    filePath: __DIR__ . '/output/',
    fileName: 'CFDI_CARTA_PORTE'
);

if ($result->isSuccess()) {
    echo "✅ CFDI con Carta Porte procesado exitosamente\n";
    echo "📁 Archivo generado: " . $result->getFilePath() . "\n";
} else {
    echo "❌ Error al procesar CFDI: " . $result->getMessage() . "\n";
    if ($result->getErrors()) {
        echo "🔍 Errores: " . implode(', ', $result->getErrors()) . "\n";
    }
}

echo "\n🎉 Ejemplo completado!\n";
echo "\n📚 Este ejemplo demuestra:\n";
echo "  ✅ Creación de CFDI con complemento de Carta Porte 3.1\n";
echo "  ✅ Configuración de ubicaciones (origen y destino)\n";
echo "  ✅ Configuración de mercancías y totales\n";
echo "  ✅ Configuración de operador de transporte\n";
echo "  ✅ Procesamiento completo con Finkok\n";
echo "  ✅ Generación y guardado de XML\n";
