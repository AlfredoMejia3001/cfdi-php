# CFDI PHP SDK - Ejemplos de Uso

Este archivo contiene ejemplos prácticos de uso para todas las clases del SDK CFDI.

## 📋 Tabla de Contenidos

1. [CFDI40.php - Facturación Básica](#cfdi40php---facturación-básica)
2. [RecepcionPagos.php - Recepción de Pagos](#recepcionpagosphp---recepción-de-pagos)
3. [CartaPorte31.php - Carta Porte](#cartaporte31php---carta-porte)
4. [Nomina12.php - Nómina](#nomina12php---nómina)
5. [Ejemplos Avanzados](#ejemplos-avanzados)

## 🔧 Configuración Inicial

```php
<?php
// Configuración común para todos los ejemplos
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/Mexico_City');

// Credenciales de Finkok (reemplazar con las tuyas)
$finkokUser = "tu_usuario@finkok.com.mx";
$finkokPass = "tu_password";

// Función helper para mostrar resultados
function mostrarResultado($resultado, $errores, $archivo) {
    if ($resultado === true) {
        echo "✅ $archivo generado exitosamente\n";
        echo "📁 Archivo guardado como: $archivo.xml\n";
    } else {
        echo "❌ Error al generar $archivo:\n";
        echo "Error: " . $errores . "\n";
    }
    echo "----------------------------------------\n";
}
?>
```

---

## 1. CFDI40.php - Facturación Básica

### Ejemplo 1: Factura Simple

```php
<?php
require_once 'src/CFDI40.php';

$cfdi = new CFDI40();

// Configurar CFDI principal
$cfdi->CFDI40(
    '30001000000500003416',  // NoCertificado
    'A',                     // Serie
    '12345',                 // Folio
    '2024-01-15T10:30:00',  // Fecha
    '1000.00',              // SubTotal
    'MXN',                  // Moneda
    '1160.00',              // Total
    'I',                    // TipoDeComprobante
    '01',                   // Exportacion
    'PUE',                  // MetodoPago
    '01',                   // FormaPago
    '06500'                 // LugarExpedicion
);

// Agregar emisor
$cfdi->AgregarEmisor(
    'XAXX010101000',        // RFC
    'EMPRESA DEMO SA DE CV', // Nombre
    '601'                   // RegimenFiscal
);

// Agregar receptor
$cfdi->AgregarReceptor(
    'XEXX010101000',        // RFC
    'CLIENTE DEMO SA DE CV', // Nombre
    '601',                  // RegimenFiscalReceptor
    'G01',                  // UsoCFDI
    '06500'                 // DomicilioFiscalReceptor
);

// Agregar concepto
$cfdi->AgregarConcepto(
    '84111506',             // ClaveProdServ
    'PROD001',              // NoIdentificacion
    '1.00',                 // Cantidad
    'H87',                  // ClaveUnidad
    'Pieza',                // Unidad
    'Servicio de consultoría', // Descripcion
    '1000.00',              // ValorUnitario
    '1000.00',              // Importe
    '02'                    // ObjetoImp
);

// Agregar impuestos
$cfdi->AgregarImpuestosTotales(
    '0.00',                 // TotalImpuestosRetenidos
    '160.00'                // TotalImpuestosTrasladados
);

$cfdi->AgregarTrasladoTotal(
    '1000.00',              // Base
    '160.00',               // Importe
    '002',                  // Impuesto (IVA)
    '0.160000',             // TasaOCuota
    'Tasa'                  // TipoFactor
);

// Generar XML
$errores = "";
$resultado = $cfdi->CrearCFDIXML(
    $finkokUser,
    $finkokPass,
    $errores,
    "./",
    "FACTURA_SIMPLE"
);

mostrarResultado($resultado, $errores, "FACTURA_SIMPLE");
?>
```

### Ejemplo 2: Factura con Múltiples Conceptos

```php
<?php
require_once 'src/CFDI40.php';

$cfdi = new CFDI40();

// Configurar CFDI principal
$cfdi->CFDI40(
    '30001000000500003416',  // NoCertificado
    'B',                     // Serie
    '67890',                 // Folio
    '2024-01-15T14:00:00',  // Fecha
    '2500.00',              // SubTotal
    'MXN',                  // Moneda
    '2900.00',              // Total
    'I',                    // TipoDeComprobante
    '01',                   // Exportacion
    'PUE',                  // MetodoPago
    '03',                   // FormaPago
    '06500'                 // LugarExpedicion
);

// Agregar emisor y receptor
$cfdi->AgregarEmisor('XAXX010101000', 'EMPRESA DEMO', '601');
$cfdi->AgregarReceptor('XEXX010101000', 'CLIENTE DEMO', '601', 'G01', '06500');

// Agregar múltiples conceptos
$cfdi->AgregarConcepto(
    '84111506',             // ClaveProdServ
    'SERV001',              // NoIdentificacion
    '2.00',                 // Cantidad
    'H87',                  // ClaveUnidad
    'Hora',                 // Unidad
    'Servicio de consultoría', // Descripcion
    '800.00',               // ValorUnitario
    '1600.00',              // Importe
    '02'                    // ObjetoImp
);

$cfdi->AgregarConcepto(
    '43211500',             // ClaveProdServ
    'PROD002',              // NoIdentificacion
    '1.00',                 // Cantidad
    'H87',                  // ClaveUnidad
    'Pieza',                // Unidad
    'Producto de software', // Descripcion
    '900.00',               // ValorUnitario
    '900.00',               // Importe
    '02'                    // ObjetoImp
);

// Agregar impuestos
$cfdi->AgregarImpuestosTotales('0.00', '400.00');
$cfdi->AgregarTrasladoTotal('2500.00', '400.00', '002', '0.160000', 'Tasa');

// Generar XML
$errores = "";
$resultado = $cfdi->CrearCFDIXML(
    $finkokUser,
    $finkokPass,
    $errores,
    "./",
    "FACTURA_MULTIPLE"
);

mostrarResultado($resultado, $errores, "FACTURA_MULTIPLE");
?>
```

---

## 2. RecepcionPagos.php - Recepción de Pagos

### Ejemplo 1: Pago Simple

```php
<?php
require_once 'src/RecepcionPagos.php';

$recepcion = new RecepcionPagos();

// Configurar CFDI principal
$recepcion->CFDI40(
    '30001000000500003416',  // NoCertificado
    'PAG',                   // Serie
    '001',                   // Folio
    '2024-01-15T10:30:00',  // Fecha
    '1000.00',              // SubTotal
    'MXN',                  // Moneda
    '1000.00',              // Total
    'P',                    // TipoDeComprobante
    '01',                   // Exportacion
    'PUE',                  // MetodoPago
    '03',                   // FormaPago
    '06500'                 // LugarExpedicion
);

// Agregar emisor y receptor
$recepcion->AgregarEmisor('XAXX010101000', 'EMPRESA DEMO', '601');
$recepcion->AgregarReceptor('XEXX010101000', 'CLIENTE DEMO', '601', 'G01', '06500');

// Agregar concepto
$recepcion->AgregarConcepto(
    '84111506',             // ClaveProdServ
    'PAG001',               // NoIdentificacion
    '1',                    // Cantidad
    'ACT',                  // ClaveUnidad
    'Servicio',             // Unidad
    'Pago de factura',      // Descripcion
    '1000.00',              // ValorUnitario
    '1000.00',              // Importe
    '01'                    // ObjetoImp
);

// Configurar Recepción de Pagos
$recepcion->RecepcionPagos(
    '2.0',                  // Version
    '2024-01-15T10:30:00', // FechaPago
    'MXN',                  // FormaDePagoP
    '1000.00'              // Monto
);

// Agregar documento relacionado
$recepcion->AgregarDoctoRelacionado(
    'uuid-del-documento',   // IdDocumento
    'MXN',                  // MonedaDR
    '1.000000',             // EquivalenciaDR
    '01',                   // NumParcialidad
    '1000.00',              // ImpSaldoAnt
    '1000.00',              // ImpPagado
    '1000.00',              // ImpSaldoInsoluto
    '01'                    // ObjetoImpDR
);

// Finalizar recepción de pagos
$recepcion->FinalizarRecepcionPagos();

// Generar XML
$errores = "";
$resultado = $recepcion->CrearRecepcionPagosXML(
    $finkokUser,
    $finkokPass,
    $errores,
    "./",
    "RECEPCION_PAGOS_SIMPLE"
);

mostrarResultado($resultado, $errores, "RECEPCION_PAGOS_SIMPLE");
?>
```

### Ejemplo 2: Pago Parcial

```php
<?php
<?php
require_once 'src/RecepcionPagos.php';

$recepcion = new RecepcionPagos();

// Configurar CFDI principal
$recepcion->CFDI40(
    '30001000000500003416',  // NoCertificado
    'PAG',                   // Serie
    '002',                   // Folio
    '2024-01-15T15:00:00',  // Fecha
    '500.00',               // SubTotal
    'MXN',                  // Moneda
    '500.00',               // Total
    'P',                    // TipoDeComprobante
    '01',                   // Exportacion
    'PUE',                  // MetodoPago
    '03',                   // FormaPago
    '06500'                 // LugarExpedicion
);

// Agregar emisor y receptor
$recepcion->AgregarEmisor('XAXX010101000', 'EMPRESA DEMO', '601');
$recepcion->AgregarReceptor('XEXX010101000', 'CLIENTE DEMO', '601', 'G01', '06500');

// Agregar concepto
$recepcion->AgregarConcepto(
    '84111506',             // ClaveProdServ
    'PAG002',               // NoIdentificacion
    '1',                    // Cantidad
    'ACT',                  // ClaveUnidad
    'Servicio',             // Unidad
    'Pago parcial factura', // Descripcion
    '500.00',               // ValorUnitario
    '500.00',               // Importe
    '01'                    // ObjetoImp
);

// Configurar Recepción de Pagos
$recepcion->RecepcionPagos(
    '2.0',                  // Version
    '2024-01-15T15:00:00', // FechaPago
    'MXN',                  // FormaDePagoP
    '500.00'               // Monto
);

// Agregar documento relacionado (pago parcial)
$recepcion->AgregarDoctoRelacionado(
    'uuid-del-documento',   // IdDocumento
    'MXN',                  // MonedaDR
    '1.000000',             // EquivalenciaDR
    '02',                   // NumParcialidad
    '1000.00',              // ImpSaldoAnt
    '500.00',               // ImpPagado
    '500.00',               // ImpSaldoInsoluto
    '01'                    // ObjetoImpDR
);

// Finalizar recepción de pagos
$recepcion->FinalizarRecepcionPagos();

// Generar XML
$errores = "";
$resultado = $recepcion->CrearRecepcionPagosXML(
    $finkokUser,
    $finkokPass,
    $errores,
    "./",
    "RECEPCION_PAGOS_PARCIAL"
);

mostrarResultado($resultado, $errores, "RECEPCION_PAGOS_PARCIAL");
?>
```

---

## 3. CartaPorte31.php - Carta Porte

### Ejemplo 1: Carta Porte Básica

```php
<?php
require_once 'src/CartaPorte31.php';

$cartaPorte = new CartaPorte31();

// Configurar CFDI principal
$cartaPorte->CFDI40(
    '30001000000500003416',  // NoCertificado
    'CCP',                   // Serie
    '001',                   // Folio
    '2024-01-15T10:30:00',  // Fecha
    '1000.00',              // SubTotal
    'MXN',                  // Moneda
    '1160.00',              // Total
    'T',                    // TipoDeComprobante
    '01',                   // Exportacion
    'PUE',                  // MetodoPago
    '01',                   // FormaPago
    '06500'                 // LugarExpedicion
);

// Agregar emisor y receptor
$cartaPorte->AgregarEmisor('XAXX010101000', 'EMPRESA DEMO', '601');
$cartaPorte->AgregarReceptor('XEXX010101000', 'CLIENTE DEMO', '601', 'G01', '06500');

// Agregar concepto
$cartaPorte->AgregarConcepto(
    '78101800',             // ClaveProdServ
    'CCP001',               // NoIdentificacion
    '1',                    // Cantidad
    'H87',                  // ClaveUnidad
    'Pieza',                // Unidad
    'Transporte de carga',  // Descripcion
    '1000.00',              // ValorUnitario
    '1000.00',              // Importe
    '02'                    // ObjetoImp
);

// Agregar impuestos
$cartaPorte->AgregarImpuestosTotales('0.00', '160.00');
$cartaPorte->AgregarTrasladoTotal('1000.00', '160.00', '002', '0.160000', 'Tasa');

// Configurar Carta Porte
$cartaPorte->CartaPorte31(
    'No',                   // TranspInternac
    'Salida',               // EntradaSalidaMerc
    '01',                   // ViaEntradaSalida
    '1',                    // TotalDistRec
    'No',                   // RegistroISTMO
    '01',                   // UbicacionPoloOrigen
    '01',                   // UbicacionPoloDestino
    null,                   // IdCCP
    'MEX'                   // PaisOrigenDestino
);

// Agregar ubicaciones
$cartaPorte->AgregarUbicacion(
    'Origen',               // TipoUbicacion
    '2024-01-15T08:00:00', // FechaHoraProgLlegada
    'MEX',                  // ClaveEntFed
    '01',                   // IdUbicacion
    'Origen',               // TipoEstacion
    '01',                   // NumEstacion
    'Nombre de la estación' // NombreEstacion
);

$cartaPorte->AgregarUbicacion(
    'Destino',              // TipoUbicacion
    '2024-01-15T18:00:00', // FechaHoraProgLlegada
    'MEX',                  // ClaveEntFed
    '02',                   // IdUbicacion
    '01',                   // TipoEstacion
    '02',                   // NumEstacion
    'Estación destino'      // NombreEstacion
);

// Agregar mercancías
$cartaPorte->AgregarMercancia(
    '001',                  // ClaveUnidadPeso
    '100.00',              // PesoBruto
    'MEX',                  // PaisOrigenDestino
    '01',                   // ClaveUnidadPeso
    '100.00',              // PesoNeto
    'Mercancía general'    // Descripcion
);

// Finalizar carta porte
$cartaPorte->FinalizarCartaPorte();

// Generar XML
$errores = "";
$resultado = $cartaPorte->CrearCartaPorteXML(
    $finkokUser,
    $finkokPass,
    $errores,
    "./",
    "CARTA_PORTE_BASICA"
);

mostrarResultado($resultado, $errores, "CARTA_PORTE_BASICA");
?>
```

---

## 4. Nomina12.php - Nómina

### Ejemplo 1: Nómina Básica

```php
<?php
require_once 'src/Nomina12.php';

$nomina = new Nomina12();

// Configurar CFDI principal
$nomina->CFDI40(
    '30001000000500003416',  // NoCertificado
    'NOM',                   // Serie
    '001',                   // Folio
    '2024-01-15T08:00:00',  // Fecha
    '5000.00',              // SubTotal
    'MXN',                  // Moneda
    '5000.00',              // Total
    'N',                    // TipoDeComprobante
    '01',                   // Exportacion
    'PUE',                  // MetodoPago
    '03',                   // FormaPago
    '42501'                 // LugarExpedicion
);

// Agregar emisor y receptor
$nomina->AgregarEmisor('EKU9003173C9', 'EMPRESA EJEMPLO', '601');
$nomina->AgregarReceptor('XAXX010101000', 'JUAN PEREZ', '616', 'CN01', '80290');

// Agregar concepto
$nomina->AgregarConcepto(
    '84111506',             // ClaveProdServ
    'NOM001',               // NoIdentificacion
    '1',                    // Cantidad
    'ACT',                  // ClaveUnidad
    'Servicio',             // Unidad
    'Pago de nómina',       // Descripcion
    '5000.00',              // ValorUnitario
    '5000.00',              // Importe
    '01'                    // ObjetoImp
);

// Configurar nómina
$nomina->Nomina12(
    '1.2',                  // Version
    'O',                    // TipoNomina
    '2024-01-15T08:00:00', // FechaPago
    '2024-01-01T00:00:00', // FechaInicialPago
    '2024-01-15T23:59:59', // FechaFinalPago
    '15'                    // NumDiasPagados
);

// Agregar receptor de nómina
$nomina->AgregarReceptorNomina(
    'PEGJ800101HDFXXX01',   // Curp
    null,                    // NumSeguridadSocial
    null,                    // FechaInicioRelLaboral
    null,                    // Antigüedad
    '01',                    // TipoContrato
    null,                    // Sindicalizado
    null,                    // TipoJornada
    '02',                    // TipoRegimen
    'EMP001',                // NumEmpleado
    null,                    // Departamento
    null,                    // Puesto
    null,                    // RiesgoPuesto
    '04',                    // PeriodicidadPago
    null,                    // Banco
    null,                    // CuentaBancaria
    null,                    // SalarioBaseCotApor
    null,                    // SalarioDiarioIntegrado
    'MEX'                    // ClaveEntFed
);

// Agregar percepción
$nomina->AgregarPercepcion(
    '001',                   // TipoPercepcion
    '001',                   // Clave
    'Sueldo ordinario',      // Concepto
    '5000.00',              // ImporteGravado
    '0.00'                  // ImporteExento
);

// Finalizar nómina
$nomina->FinalizarNomina(
    '5000.00',              // TotalSueldos
    null,                    // TotalSeparacionIndemnizacion
    null,                    // TotalJubilacionPensionRetiro
    '5000.00',              // TotalGravado
    '0.00',                 // TotalExento
    null,                    // TotalOtrasDeducciones
    null                     // TotalImpuestosRetenidos
);

// Generar XML
$errores = "";
$resultado = $nomina->CrearNominaXML(
    $finkokUser,
    $finkokPass,
    $errores,
    "./",
    "NOMINA_BASICA"
);

mostrarResultado($resultado, $errores, "NOMINA_BASICA");
?>
```

### Ejemplo 2: Nómina con Deducciones

```php
<?php
require_once 'src/Nomina12.php';

$nomina = new Nomina12();

// Configurar CFDI principal
$nomina->CFDI40(
    '30001000000500003416',  // NoCertificado
    'NOM',                   // Serie
    '002',                   // Folio
    '2024-01-15T08:00:00',  // Fecha
    '8000.00',              // SubTotal
    'MXN',                  // Moneda
    '8000.00',              // Total
    'N',                    // TipoDeComprobante
    '01',                   // Exportacion
    'PUE',                  // MetodoPago
    '03',                   // FormaPago
    '42501'                 // LugarExpedicion
);

// Agregar emisor y receptor
$nomina->AgregarEmisor('EKU9003173C9', 'EMPRESA EJEMPLO', '601');
$nomina->AgregarReceptor('XAXX010101000', 'MARIA GARCIA', '616', 'CN01', '80290');

// Agregar concepto
$nomina->AgregarConcepto(
    '84111506',             // ClaveProdServ
    'NOM002',               // NoIdentificacion
    '1',                    // Cantidad
    'ACT',                  // ClaveUnidad
    'Servicio',             // Unidad
    'Pago de nómina',       // Descripcion
    '8000.00',              // ValorUnitario
    '8000.00',              // Importe
    '01'                    // ObjetoImp
);

// Configurar nómina
$nomina->Nomina12(
    '1.2',                  // Version
    'O',                    // TipoNomina
    '2024-01-15T08:00:00', // FechaPago
    '2024-01-01T00:00:00', // FechaInicialPago
    '2024-01-15T23:59:59', // FechaFinalPago
    '15'                    // NumDiasPagados
);

// Agregar receptor de nómina
$nomina->AgregarReceptorNomina(
    'GARM800101MDFXXX02',   // Curp
    '12345678901',          // NumSeguridadSocial
    '2020-01-01T00:00:00', // FechaInicioRelLaboral
    'P4Y',                  // Antigüedad
    '01',                    // TipoContrato
    'No',                    // Sindicalizado
    '01',                    // TipoJornada
    '02',                    // TipoRegimen
    'EMP002',                // NumEmpleado
    'Administración',        // Departamento
    'Contadora',             // Puesto
    '1',                     // RiesgoPuesto
    '04',                    // PeriodicidadPago
    '012',                   // Banco
    '123456789012345678',    // CuentaBancaria
    '8000.00',               // SalarioBaseCotApor
    '533.33',                // SalarioDiarioIntegrado
    'MEX'                    // ClaveEntFed
);

// Agregar percepciones
$nomina->AgregarPercepcion(
    '001',                   // TipoPercepcion
    '001',                   // Clave
    'Sueldo ordinario',      // Concepto
    '7000.00',              // ImporteGravado
    '0.00'                  // ImporteExento
);

$nomina->AgregarPercepcion(
    '002',                   // TipoPercepcion
    '002',                   // Clave
    'Aguinaldo',             // Concepto
    '1000.00',              // ImporteGravado
    '0.00'                  // ImporteExento
);

// Agregar deducciones
$nomina->AgregarDeduccion(
    '001',                   // TipoDeduccion
    '001',                   // Clave
    'IMSS',                  // Concepto
    '600.00'                 // Importe
);

$nomina->AgregarDeduccion(
    '002',                   // TipoDeduccion
    '002',                   // Clave
    'ISR',                   // Concepto
    '1000.00'                // Importe
);

// Finalizar nómina
$nomina->FinalizarNomina(
    '8000.00',              // TotalSueldos
    null,                    // TotalSeparacionIndemnizacion
    null,                    // TotalJubilacionPensionRetiro
    '8000.00',              // TotalGravado
    '0.00',                 // TotalExento
    '1600.00',              // TotalOtrasDeducciones
    '1000.00'               // TotalImpuestosRetenidos
);

// Generar XML
$errores = "";
$resultado = $nomina->CrearNominaXML(
    $finkokUser,
    $finkokPass,
    $errores,
    "./",
    "NOMINA_CON_DEDUCCIONES"
);

mostrarResultado($resultado, $errores, "NOMINA_CON_DEDUCCIONES");
?>
```

---

## 5. Ejemplos Avanzados

### Ejemplo 1: CFDI con Múltiples Complementos

```php
<?php
// Este ejemplo muestra cómo combinar múltiples complementos
// Nota: Esto requiere implementación personalizada

echo "🔧 Ejemplo avanzado: CFDI con múltiples complementos\n";
echo "Este ejemplo requiere implementación personalizada para combinar\n";
echo "diferentes complementos en un solo CFDI.\n";
echo "----------------------------------------\n";
?>
```

### Ejemplo 2: Validación Personalizada

```php
<?php
// Función para validar XML contra esquema XSD
function validarXMLContraEsquema($xmlFile, $xsdFile) {
    $xml = new DOMDocument();
    $xml->load($xmlFile);
    
    if ($xml->schemaValidate($xsdFile)) {
        echo "✅ XML válido según esquema XSD\n";
        return true;
    } else {
        echo "❌ XML no válido según esquema XSD\n";
        return false;
    }
}

// Función para validar estructura de directorios
function validarEstructuraDirectorios($ruta) {
    if (!is_dir($ruta)) {
        if (!mkdir($ruta, 0755, true)) {
            throw new Exception("No se pudo crear el directorio: $ruta");
        }
    }
    
    if (!is_writable($ruta)) {
        throw new Exception("El directorio no es escribible: $ruta");
    }
    
    echo "✅ Directorio válido: $ruta\n";
    return true;
}

echo "🔧 Funciones de validación personalizada disponibles\n";
echo "----------------------------------------\n";
?>
```

---

## 📝 Notas de Uso

### Configuración de Entorno
- Asegúrate de tener PHP 7.4 o superior
- Habilita las extensiones SOAP y DOM
- Configura la zona horaria correcta

### Manejo de Errores
- Siempre verifica el resultado de la generación
- Revisa los mensajes de error para debugging
- Usa try-catch para manejar excepciones

### Validaciones
- Todas las clases incluyen validaciones de Finkok
- Verifica que las credenciales sean correctas
- Confirma que el RFC emisor esté activo

### Archivos Generados
- Los XML se guardan en la ruta especificada
- Los nombres de archivo se pueden personalizar
- Verifica permisos de escritura en el directorio

---

## 🚀 Próximos Pasos

1. **Prueba los ejemplos básicos** con tus credenciales
2. **Personaliza los parámetros** según tus necesidades
3. **Implementa validaciones adicionales** si es necesario
4. **Integra en tu aplicación** usando los métodos disponibles

Para más información, consulta:
- [README.md](README.md) - Documentación completa
- [INDEX.md](INDEX.md) - Índice de documentación
- Archivos README específicos por clase
