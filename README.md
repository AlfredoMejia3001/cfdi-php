# CFDI PHP SDK - Documentación Completa

SDK completo para la generación de Comprobantes Fiscales Digitales por Internet (CFDI) en PHP, incluyendo soporte para CFDI 4.0, Recepción de Pagos, Carta Porte y Nómina.

## 📋 Características

- ✅ **CFDI 4.0**: Generación de facturas electrónicas estándar
- ✅ **Recepción de Pagos**: Complemento para pagos y abonos
- ✅ **Carta Porte**: Complemento para transporte de mercancías
- ✅ **Nómina 1.2**: Complemento para nóminas y recursos humanos
- ✅ **Validación Finkok**: Integración con servicios de validación
- ✅ **XML Válido**: Generación de XML conforme a esquemas del SAT
- ✅ **Manejo de Errores**: Validaciones robustas y mensajes claros

## 🏗️ Estructura del Proyecto

```
cfdi-php/
├── src/
│   ├── CFDI40.php              # Clase principal para CFDI 4.0
│   ├── RecepcionPagos.php      # Clase para Recepción de Pagos
│   ├── CartaPorte31.php        # Clase para Carta Porte 3.1
│   └── Nomina12.php            # Clase para Nómina 1.2
├── composer.json
├── README.md                    # Esta documentación principal
├── README_CartaPorte31.md      # Documentación específica de Carta Porte
└── README_Nomina12.md          # Documentación específica de Nómina
```

## 🚀 Instalación

1. **Clonar el repositorio:**
```bash
git clone https://github.com/AlfredoMejia3001/cfdi-php.git  
cd cfdi-php-sdk/cfdi-php
```

2. **Instalar dependencias:**
```bash
composer install
```

3. **Verificar extensiones PHP requeridas:**
```bash
php -m | grep -E "(soap|dom)"
```

## 📚 Clases Disponibles

### 1. CFDI40.php - Facturación Estándar
Genera CFDI 4.0 básicos sin complementos específicos.

### 2. RecepcionPagos.php - Recepción de Pagos
Genera CFDI con complemento de Recepción de Pagos para abonos y pagos.

### 3. CartaPorte31.php - Carta Porte 3.1
Genera CFDI con complemento de Carta Porte para transporte de mercancías.

### 4. Nomina12.php - Nómina 1.2
Genera CFDI con complemento de Nómina para recursos humanos.

## 🔧 Configuración Inicial

### Credenciales de Finkok
Para usar las validaciones, necesitas una cuenta en Finkok:

```php
$finkokUser = "tu_usuario@finkok.com.mx";
$finkokPass = "tu_password";
```

### Configuración del Entorno
```php
// Habilitar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configurar zona horaria
date_default_timezone_set('America/Mexico_City');
```

## 📖 Guías de Uso por Clase

### 1. CFDI40.php - Facturación Básica

#### Uso Básico
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
    "FACTURA_EJEMPLO"
);

if ($resultado === true) {
    echo "✅ CFDI generado exitosamente";
} else {
    echo "❌ Error: " . $errores;
}
```

#### Tipos de Comprobante
- `I`: Ingreso
- `E`: Egreso
- `T`: Traslado
- `N`: Nómina
- `P`: Pago

#### Métodos de Pago
- `PUE`: Pago en una sola exhibición
- `PPD`: Pago en parcialidades o diferido

#### Formas de Pago
- `01`: Efectivo
- `02`: Cheque nominativo
- `03`: Transferencia electrónica
- `04`: Tarjeta de crédito
- `28`: Tarjeta de débito
- `99`: Por definir

### 2. RecepcionPagos.php - Recepción de Pagos

#### Uso Básico
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
    'P',                    // TipoDeComprobante (P = Pago)
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
    "RECEPCION_PAGOS_EJEMPLO"
);

if ($resultado === true) {
    echo "✅ Recepción de Pagos generada exitosamente";
} else {
    echo "❌ Error: " . $errores;
}
```

### 3. CartaPorte31.php - Carta Porte 3.1

#### Uso Básico
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
    'T',                    // TipoDeComprobante (T = Traslado)
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
    "CARTA_PORTE_EJEMPLO"
);

if ($resultado === true) {
    echo "✅ Carta Porte generada exitosamente";
} else {
    echo "❌ Error: " . $errores;
}
```

### 4. Nomina12.php - Nómina 1.2

#### Uso Básico
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
    'N',                    // TipoDeComprobante (N = Nómina)
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
    'O',                    // TipoNomina (O = Ordinaria)
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
    "NOMINA_EJEMPLO"
);

if ($resultado === true) {
    echo "✅ Nómina generada exitosamente";
} else {
    echo "❌ Error: " . $errores;
}
```

## 🔍 Validaciones y Errores

### Validaciones de Finkok
Todas las clases incluyen validaciones automáticas:

1. **Credenciales**: Verificación de usuario y contraseña
2. **RFC Emisor**: Estado activo en la cuenta
3. **Servicios**: Disponibilidad de servicios SOAP

### Manejo de Errores
```php
$errores = "";
$errorE = null;

$resultado = $clase->CrearXML(
    $finkokUser,
    $finkokPass,
    $errores,        // Variable para mensajes de error
    "./",            // Ruta de guardado
    "ARCHIVO",       // Nombre del archivo
    $errorE          // Variable para excepciones
);

if ($resultado === true) {
    echo "✅ XML generado exitosamente";
} else {
    echo "❌ Error: " . $errores;
    
    if ($errorE !== null) {
        echo "Error adicional: " . $errorE->getMessage();
    }
}
```

### Errores Comunes

#### Error de Credenciales
```
Error: Error de autenticación: Credenciales inválidas
```
**Solución**: Verificar credenciales de Finkok.

#### RFC Inactivo
```
Error: El RFC emisor se encuentra inactivo en la cuenta
```
**Solución**: Activar RFC en cuenta de Finkok.

#### Error de Conexión
```
Error: Error en el servicio de autenticación: Connection timed out
```
**Solución**: Verificar conectividad a internet y servicios de Finkok.

## 📁 Archivos Generados

### Estructura de Nombres
- **CFDI40**: `FACTURA_EJEMPLO.xml`
- **Recepción de Pagos**: `RECEPCION_PAGOS_EJEMPLO.xml`
- **Carta Porte**: `CARTA_PORTE_EJEMPLO.xml`
- **Nómina**: `NOMINA_EJEMPLO.xml`

### Ubicación de Archivos
Los archivos se guardan en la ruta especificada en el parámetro `$Ruta` del método de creación.

## 🛠️ Configuración Avanzada

### Personalización de Rutas
```php
// Ruta personalizada
$rutaPersonalizada = "/var/www/html/cfdi/";
$nombreArchivo = "FACTURA_" . date('Y-m-d_H-i-s');

$resultado = $clase->CrearXML(
    $finkokUser,
    $finkokPass,
    $errores,
    $rutaPersonalizada,
    $nombreArchivo
);
```

### Validación de Esquemas
```php
// Validar XML contra esquema XSD
$xml = new DOMDocument();
$xml->load('archivo.xml');

if ($xml->schemaValidate('esquema.xsd')) {
    echo "✅ XML válido según esquema";
} else {
    echo "❌ XML no válido según esquema";
}
```

### Logging de Errores
```php
// Configurar logging
ini_set('log_errors', 1);
ini_set('error_log', 'cfdi_errors.log');

// Los errores se registrarán en el archivo especificado
```

## 📊 Ejemplos de Uso por Escenario

### Escenario 1: Facturación Básica
```php
// Usar CFDI40.php para facturas simples
require_once 'src/CFDI40.php';
$cfdi = new CFDI40();
// ... configuración básica
```

### Escenario 2: Facturación con Transporte
```php
// Usar CartaPorte31.php para envíos
require_once 'src/CartaPorte31.php';
$cartaPorte = new CartaPorte31();
// ... configuración con ubicaciones
```

### Escenario 3: Nómina Completa
```php
// Usar Nomina12.php para recursos humanos
require_once 'src/Nomina12.php';
$nomina = new Nomina12();
// ... configuración con percepciones y deducciones
```

### Escenario 4: Recepción de Pagos
```php
// Usar RecepcionPagos.php para abonos
require_once 'src/RecepcionPagos.php';
$recepcion = new RecepcionPagos();
// ... configuración con documentos relacionados
```

## 🔧 Troubleshooting

### Problemas de Conexión SOAP
```php
// Verificar extensiones PHP
if (!extension_loaded('soap')) {
    die('Extensión SOAP no está habilitada');
}

// Verificar conectividad
$url = "https://demo-facturacion.finkok.com/servicios/soap/utilities.wsdl";
$context = stream_context_create(['http' => ['timeout' => 10]]);
$result = @file_get_contents($url, false, $context);

if ($result === false) {
    die('No se puede conectar a Finkok');
}
```

### Problemas de Memoria
```php
// Aumentar límite de memoria para archivos grandes
ini_set('memory_limit', '512M');

// Limpiar memoria después de generar XML
unset($xml);
gc_collect_cycles();
```

### Problemas de Permisos
```bash
# Verificar permisos de escritura
chmod 755 /ruta/destino
chown www-data:www-data /ruta/destino
```

## 📚 Recursos Adicionales

### Documentación del SAT
- [CFDI 4.0](http://omawww.sat.gob.mx/tramitesyservicios/Paginas/anexo_20.htm)
- [Recepción de Pagos](http://omawww.sat.gob.mx/tramitesyservicios/Paginas/recepcion_de_pagos.htm)
- [Carta Porte](http://omawww.sat.gob.mx/tramitesyservicios/paginas/complemento_carta_porte.htm)
- [Nómina](https://www.sat.gob.mx/cfd/nomina)

### Catálogos del SAT
- [Catálogo de Productos y Servicios](http://omawww.sat.gob.mx/tramitesyservicios/Paginas/documentos/catCFDI_V_4_20250808.xls)
- [Catálogo de Unidades](http://omawww.sat.gob.mx/tramitesyservicios/Paginas/documentos/catCFDI_V_4_20250808.xls)
- [Catálogo de Impuestos](http://omawww.sat.gob.mx/tramitesyservicios/Paginas/documentos/catCFDI_V_4_20250808.xls)

### Servicios de Finkok
- [Documentación API](https://www.finkok.com/developers/) Estan los .md como documentacion

## 🤝 Contribuciones

Para contribuir al proyecto:

1. Fork el repositorio
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -am 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Crea un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 📞 Soporte

Para soporte técnico:

- **Issues**: Crear un issue en GitHub
- **Email**: alfredo.mejia@finkok.com
- **Documentación**: Esta documentación y archivos README específicos

---

**Nota**: Esta documentación se actualiza regularmente. Para la versión más reciente, consulta el repositorio de GitHub.
