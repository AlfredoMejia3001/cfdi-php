<?php
require_once 'src/CartaPorte31.php';

// Ejemplo completo de CFDI con Carta Porte usando la nueva estructura

// Configuración de credenciales de Finkok
$finkokUser = "amejia@finkok.com.mx";
$finkokPass = "yN8Q4vp,LPQQ6*y";

// Crear instancia de CartaPorte31
$cartaPorte = new CartaPorte31();

// Configurar CFDI principal (como CFDI40.php)
$cartaPorte->CFDI40(
    "30001000000500003416", // NoCertificado
    "SerieCCP31", // Serie
    "CP3.1", // Folio
    "2025-07-03T09:09:22", // Fecha
    "26232.75", // SubTotal
    "MXN", // Moneda
    "29380.68", // Total
    "I", // TipoDeComprobante
    "01", // Exportacion
    "PUE", // MetodoPago
    "01", // FormaPago
    "42501", // LugarExpedicion
    null, // CondicionesDePago
    null, // Descuento
    null, // TipoCambio
    null // Confirmacion
);

// Agregar emisor
$cartaPorte->AgregarEmisor(
    "EKU9003173C9", // Rfc
    "ESCUELA KEMPER URGATE", // NombreE
    "601" // RegimenFiscalE
);

// Agregar receptor
$cartaPorte->AgregarReceptor(
    "MASO451221PM4", // RfcR
    "MARIA OLIVIA MARTINEZ SAGAZ", // NombreR
    "616", // RegimenFiscalReceptor
    "S01", // UsoCFDI
    "80290" // DomicilioFiscalReceptor
);

// Agregar concepto principal
$cartaPorte->AgregarConcepto(
    "78101800", // ClaveProdServ
    "UT421511", // NoIdentificacion
    "1", // Cantidad
    "H87", // ClaveUnidad
    "Pieza", // Unidad
    "Transporte de carga por carretera", // Descripcion
    "26232.75", // ValorUnitario
    "26232.75", // Importe
    "02" // ObjetoImp
);

// Agregar impuestos totales
$cartaPorte->AgregarImpuestosTotales(
    "1049.31", // TotalImpuestosRetenidos
    "4197.24" // TotalImpuestosTrasladados
);

// Agregar retención total
$cartaPorte->AgregarRetencionTotal(
    "002", // Impuesto
    "1049.31" // Importe
);

// Agregar traslado total
$cartaPorte->AgregarTrasladoTotal(
    "26232.75", // Base
    "4197.24", // Importe
    "002", // Impuesto
    "0.160000", // TasaOCuota
    "Tasa" // TipoFactor
);

// Configurar Carta Porte (complemento)
$cartaPorte->CartaPorte31(
    "No", // TranspInternac
    "Entrada", // EntradaSalidaMerc
    "01", // ViaEntradaSalida
    "1", // TotalDistRec
    "Sí", // RegistroISTMO
    "01", // UbicacionPoloOrigen
    "01", // UbicacionPoloDestino
    "CCCBCD94-870A-4332-A52A-A52AA52AA52A", // IdCCP
    "MEX" // PaisOrigenDestino
);

// Agregar regímenes aduaneros
$cartaPorte->AgregarRegimenAduanero("IMD");
$cartaPorte->AgregarRegimenesAduaneros();

// Agregar ubicaciones
// Ubicación de origen
$cartaPorte->AgregarUbicacion(
    "Origen", // TipoUbicacion
    "URE180429TM6", // RFCRemitenteDestinatario
    "2023-08-01T00:00:00", // FechaHoraSalidaLlegada
    "OR101010", // IDUbicacion
    "NombreRemitenteDestinatario1" // NombreRemitenteDestinatario
);

// Agregar domicilio a la ubicación de origen
$cartaPorte->AgregarDomicilio(
    "Calle1", // Calle
    "211", // NumeroExterior
    "212", // NumeroInterior
    "1957", // Colonia
    "13", // Localidad
    "casa blanca", // Referencia
    "011", // Municipio
    "CMX", // Estado
    "MEX", // Pais
    "13250" // CodigoPostal
);

// Ubicación de destino
$cartaPorte->AgregarUbicacion(
    "Destino", // TipoUbicacion
    "URE180429TM6", // RFCRemitenteDestinatario
    "2023-08-01T00:00:01", // FechaHoraSalidaLlegada
    "DE202020", // IDUbicacion
    "NombreRemitenteDestinatario2" // NombreRemitenteDestinatario
);

// Agregar domicilio a la ubicación de destino
$cartaPorte->AgregarDomicilio(
    "Calle2", // Calle
    "214", // NumeroExterior
    "215", // NumeroInterior
    "0347", // Colonia
    "23", // Localidad
    "casa negra", // Referencia
    "004", // Municipio
    "COA", // Estado
    "MEX", // Pais
    "25350" // CodigoPostal
);

// Agregar mercancías
$cartaPorte->AgregarMercancia(
    "11121900", // BienesTransp
    "Accesorios de equipo de telefonía", // Descripcion
    "1.0", // Cantidad
    "XBX", // ClaveUnidad
    null, // Unidad
    null, // Dimensiones
    "No", // MaterialPeligroso
    null, // CveMaterialPeligroso
    null, // Embalaje
    null, // DescripEmbalaje
    "1", // PesoEnKg
    null, // ValorMercancia
    null, // Moneda
    "6309000100", // FraccionArancelaria
    null, // UUIDComercioExt
    null, // TipoMateria
    null, // DescripcionMateria
    null // NoIdentificacion
);

// Agregar cantidad transporta
$cartaPorte->AgregarCantidadTransporta(
    "1", // Cantidad
    "OR101010", // IDOrigen
    "DE202020" // IDDestino
);

// Agregar autotransporte
$cartaPorte->AgregarAutotransporte(
    "TPAF01", // PermSCT
    "NumPermisoSCT1", // NumPermisoSCT
    "VL", // ConfigVehicular
    "1", // PesoBrutoVehicular
    "plac892", // PlacaVM
    2020, // AnioModeloVM
    "AseguraRespCivil", // AseguraRespCivil
    "123456789" // PolizaRespCivil
);

// Agregar remolque
$cartaPorte->AgregarRemolque(
    "CTR004", // SubTipoRem
    "VL45K98" // Placa
);

// Agregar figura de transporte
$cartaPorte->AgregarFiguraTransporte(
    "01", // TipoFigura
    "URE180429TM6", // RFCFigura
    "NumLicencia1", // NumLicencia
    "NombreFigura1" // NombreFigura
);

// Agregar parte de transporte
$cartaPorte->AgregarParteTransporte("PT01");

// Finalizar Carta Porte
$cartaPorte->FinalizarCartaPorte(
    "1.0", // PesoBrutoTotal
    "XBX", // UnidadPeso
    "1", // NumTotalMercancias
    "Sí" // LogisticaInversaRecoleccionDevolucion
);

// Crear el CFDI completo con validaciones de Finkok
$errores = "";
$errorE = "";

$xmlResult = $cartaPorte->CrearCartaPorteXML(
    $finkokUser,
    $finkokPass,
    $errores,
    "./output", // Ruta donde guardar
    "CFDI_CON_CARTA_PORTE", // Nombre del archivo
    $errorE
);

if ($xmlResult !== false) {
    echo "✅ CFDI con Carta Porte creado exitosamente\n";
    echo "📁 Archivo guardado en: ./output/CFDI_CON_CARTA_PORTE.xml\n";
    echo "📄 Contenido del XML:\n";
    echo "----------------------------------------\n";
    
    // Leer y mostrar el contenido del archivo generado
    $xmlFile = "./output/CFDI_CON_CARTA_PORTE.xml";
    if (file_exists($xmlFile)) {
        echo file_get_contents($xmlFile);
    }
    
    echo "----------------------------------------\n";
} else {
    echo "❌ Error al crear CFDI con Carta Porte\n";
    echo "🔍 Errores: " . $errores . "\n";
    if (!empty($errorE)) {
        echo "🔍 Error adicional: " . $errorE . "\n";
    }
}

// También puedes obtener solo el complemento Carta Porte (sin validaciones)
echo "\n📋 Solo complemento Carta Porte (para desarrollo):\n";
echo "----------------------------------------\n";
echo $cartaPorte->CrearXML();
echo "----------------------------------------\n";
?> 