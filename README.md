# CFDI PHP SDK

SDK completo para la generación de Comprobantes Fiscales Digitales por Internet (CFDI) en PHP, incluyendo soporte para CFDI 4.0, Recepción de Pagos y Carta Porte.

## 📋 Características

- ✅ **CFDI 4.0**: Generación de facturas electrónicas estándar
- ✅ **Recepción de Pagos**: Complemento para pagos y abonos
- ✅ **Carta Porte**: Complemento para transporte de mercancías
- ✅ **Validación Finkok**: Integración con servicios de validación
- ✅ **XML Válido**: Generación de XML conforme a esquemas del SAT
- ✅ **Manejo de Errores**: Validaciones robustas y mensajes claros

## 🏗️ Estructura del Proyecto

```
cfdi-php/
├── src/
│   ├── CFDI40.php              # Clase principal para CFDI 4.0
│   ├── RecepcionPagos.php      # Clase para Recepción de Pagos
│   └── CartaPorte31.php        # Clase para Carta Porte 3.1
├── composer.json
└── README.md
```

## 🚀 Instalación

1. **Clonar el repositorio:**
```bash
git clone <repository-url>
cd cfdi-php-sdk/cfdi-php
```

2. **Instalar dependencias:**
```bash
composer install
```

## 📚 Uso

### 1. CFDI 4.0 - Facturación Estándar

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
    '601',                  // RegimenFiscal
    'S01'                   // FacAtrAdquiriente
);

// Agregar receptor
$cfdi->AgregarReceptor(
    'XEXX010101000',        // RFC
    'CLIENTE DEMO SA DE CV', // Nombre
    '601',                  // RegimenFiscalReceptor
    'G01',                  // UsoCFDI
    '06500',                // DomicilioFiscalReceptor
    'MEX',                  // ResidenciaFiscal
    '123456789'             // NumRegIdTrib
);

// Agregar concepto
$concepto = $cfdi->AgregarConcepto(
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

// Agregar impuestos al concepto
$cfdi->AgregarTraslado(
    '1000.00',              // Base
    '002',                  // Impuesto (IVA)
    'Tasa',                 // TipoFactor
    '0.160000',             // TasaOCuota
    '160.00'                // Importe
);

// Configurar impuestos totales
$cfdi->AgregarImpuestosTotales('0.00', '160.00');
$cfdi->AgregarTrasladoTotal(
    '1000.00',              // Base
    '160.00',               // Importe
    '002',                  // Impuesto
    '0.160000',             // TasaOCuota
    'Tasa'                  // TipoFactor
);

// Generar XML
$errores = '';
$resultado = $cfdi->CrearFacturaXML(
    'usuario_finkok',       // Usuario Finkok
    'password_finkok',      // Password Finkok
    $errores,               // Variable para errores
    './xml/',               // Ruta de salida
    'factura_demo'          // Nombre del archivo
);

if ($resultado) {
    echo "XML generado exitosamente";
} else {
    echo "Error: " . $errores;
}
?>
```

### 2. Recepción de Pagos

```php
<?php
require_once 'src/RecepcionPagos.php';

$pagos = new RecepcionPagos();

// Configurar CFDI principal
$pagos->CFDI40(
    '30001000000500003416',  // NoCertificado
    'P',                     // Serie
    '001',                   // Folio
    '2024-01-15T10:30:00',  // Fecha
    '0.00',                  // SubTotal
    'MXN',                   // Moneda
    '0.00',                  // Total
    'P',                     // TipoDeComprobante
    '01',                    // Exportacion
    '06500'                  // LugarExpedicion
);

// Agregar emisor y receptor
$pagos->AgregarEmisor('XAXX010101000', 'EMPRESA DEMO', '601');
$pagos->AgregarReceptor('XEXX010101000', 'CLIENTE DEMO', '601', 'G01', '06500');

// Agregar concepto mínimo
$pagos->AgregarConcepto(
    '84111506',             // ClaveProdServ
    '1.00',                 // Cantidad
    'H87',                  // ClaveUnidad
    'Pago en una sola exhibición', // Descripcion
    '0.00',                 // ValorUnitario
    '0.00',                 // Importe
    '01'                    // ObjetoImp
);

// Configurar complemento de pagos
$pagos->AgregarComplementoPagos();
$pagos->AgregarTotales(
    '0.00',                 // TotalRetencionesIVA
    '0.00',                 // TotalRetencionesISR
    '0.00',                 // TotalRetencionesIEPS
    '0.00',                 // TotalTrasladosBaseIVA16
    '0.00',                 // TotalTrasladosImpuestoIVA16
    '0.00',                 // TotalTrasladosBaseIVA8
    '0.00',                 // TotalTrasladosImpuestoIVA8
    '0.00',                 // TotalTrasladosBaseIVA0
    '0.00',                 // TotalTrasladosImpuestoIVA0
    '0.00',                 // TotalTrasladosBaseIVAExento
    '1000.00'               // MontoTotalPagos
);

// Agregar pago
$pago = $pagos->AgregarPago(
    '2024-01-15T10:30:00',  // FechaPago
    '01',                    // FormaDePagoP
    'MXN',                   // MonedaP
    '1.00',                  // TipoCambioP
    '1000.00',               // Monto
    'OP001',                 // NumOperacion
    'XAXX010101000',         // RfcEmisorCtaOrd
    'BANCO DEMO',            // NomBancoOrdExt
    '1234567890',            // CtaOrdenante
    'XEXX010101000',         // RfcEmisorCtaBen
    '0987654321',            // CtaBeneficiario
    '01',                    // TipoCadPago
    '30001000000500003416',  // CertPago
    'cadena_original_pago',  // CadPago
    'sello_pago'             // SelloPago
);

// Agregar documento relacionado
$docto = $pagos->AgregarDoctoRelacionado(
    $pago,                   // Objeto pago
    'uuid_documento_original', // IdDocumento
    'A',                     // Serie
    '12345',                 // Folio
    'MXN',                   // MonedaDR
    '1.00',                  // EquivalenciaDR
    '1',                     // NumParcialidad
    '1000.00',               // ImpSaldoAnt
    '1000.00',               // ImpPagado
    '0.00',                  // ImpSaldoInsoluto
    '02'                     // ObjetoImpDR
);

// Generar XML
$errores = '';
$resultado = $pagos->CrearFacturaXML(
    'usuario_finkok',
    'password_finkok',
    $errores,
    './xml/',
    'recepcion_pagos'
);

if ($resultado) {
    echo "XML de recepción de pagos generado exitosamente";
} else {
    echo "Error: " . $errores;
}
?>
```

### 3. Carta Porte

```php
<?php
require_once 'src/CartaPorte31.php';

$cartaPorte = new CartaPorte31();

// Configurar CFDI principal
$cartaPorte->CFDI40(
    '30001000000500003416',  // NoCertificado
    'CP',                    // Serie
    '001',                   // Folio
    '2024-01-15T10:30:00',  // Fecha
    '1000.00',               // SubTotal
    'MXN',                   // Moneda
    '1160.00',               // Total
    'T',                     // TipoDeComprobante
    '01',                    // Exportacion
    'PUE',                   // MetodoPago
    '01',                    // FormaPago
    '06500'                  // LugarExpedicion
);

// Agregar emisor y receptor
$cartaPorte->AgregarEmisor('XAXX010101000', 'EMPRESA TRANSPORTE', '601');
$cartaPorte->AgregarReceptor('XEXX010101000', 'CLIENTE DEMO', '601', 'G01', '06500');

// Agregar concepto
$cartaPorte->AgregarConcepto(
    '78101800',              // ClaveProdServ
    'SERV001',               // NoIdentificacion
    '1.00',                  // Cantidad
    'H87',                   // ClaveUnidad
    'Viaje',                 // Unidad
    'Servicio de transporte de carga', // Descripcion
    '1000.00',               // ValorUnitario
    '1000.00',               // Importe
    '02'                     // ObjetoImp
);

// Configurar Carta Porte
$cartaPorte->CartaPorte31(
    'No',                    // TranspInternac
    'Entrada',               // EntradaSalidaMerc
    '01',                    // ViaEntradaSalida
    '100',                   // TotalDistRec
    'Sí',                    // RegistroISTMO
    '01',                    // UbicacionPoloOrigen
    '01',                    // UbicacionPoloDestino
    'CCP-001-2024',          // IdCCP
    'MEX'                    // PaisOrigenDestino
);

// Agregar régimen aduanero
$cartaPorte->AgregarRegimenAduanero('IMD');
$cartaPorte->AgregarRegimenesAduaneros();

// Agregar ubicación origen
$cartaPorte->AgregarUbicacion(
    'Origen',                // TipoUbicacion
    'XAXX010101000',         // RFCRemitenteDestinatario
    '2024-01-15T08:00:00',  // FechaHoraSalidaLlegada
    'OR001',                 // IDUbicacion
    'REMITENTE DEMO',        // NombreRemitenteDestinatario
    'MEX',                   // ResidenciaFiscal
    'PM001',                 // NumEstacion
    'PUERTO DEMO',           // NombreEstacion
    'Altura',                // NavegacionTrafico
    '01',                    // TipoEstacion
    '50'                     // DistanciaRecorrida
);

// Agregar domicilio origen
$cartaPorte->AgregarDomicilio(
    'Av. Principal',         // Calle
    '123',                   // NumeroExterior
    'A',                     // NumeroInterior
    'Centro',                // Colonia
    'Ciudad Demo',           // Localidad
    'Frente al parque',      // Referencia
    '001',                   // Municipio
    'DEM',                   // Estado
    'MEX',                   // Pais
    '06500'                  // CodigoPostal
);

// Agregar ubicación destino
$cartaPorte->AgregarUbicacion(
    'Destino',               // TipoUbicacion
    'XEXX010101000',         // RFCRemitenteDestinatario
    '2024-01-15T18:00:00',  // FechaHoraSalidaLlegada
    'DE001',                 // IDUbicacion
    'DESTINATARIO DEMO',     // NombreRemitenteDestinatario
    'MEX',                   // ResidenciaFiscal
    'PM002',                 // NumEstacion
    'PUERTO DESTINO',        // NombreEstacion
    'Altura',                // NavegacionTrafico
    '01',                    // TipoEstacion
    '50'                     // DistanciaRecorrida
);

// Agregar domicilio destino
$cartaPorte->AgregarDomicilio(
    'Calle Secundaria',      // Calle
    '456',                   // NumeroExterior
    'B',                     // NumeroInterior
    'Industrial',            // Colonia
    'Ciudad Destino',        // Localidad
    'Cerca del puerto',      // Referencia
    '002',                   // Municipio
    'DEM',                   // Estado
    'MEX',                   // Pais
    '06500'                  // CodigoPostal
);

// Agregar mercancía
$cartaPorte->AgregarMercancia(
    '11121900',              // BienesTransp
    'Carga general',         // Descripcion
    '1.00',                  // Cantidad
    'XBX',                   // ClaveUnidad
    'Tonelada',              // Unidad
    '2x2x3m',                // Dimensiones
    'No',                    // MaterialPeligroso
    '0004',                  // CveMaterialPeligroso
    '1A1',                   // Embalaje
    'Caja de cartón',        // DescripEmbalaje
    '1000.00',               // PesoEnKg
    '1000.00',               // ValorMercancia
    'MXN',                   // Moneda
    '6309000100',            // FraccionArancelaria
    'uuid_comercio_ext',     // UUIDComercioExt
    '01',                    // TipoMateria
    'Carga general'          // DescripcionMateria
);

// Agregar detalle de mercancía
$cartaPorte->AgregarDetalleMercancia(
    'KGM',                   // UnidadPesoMerc
    '1000.00',               // PesoBruto
    '950.00',                // PesoNeto
    '50.00'                  // PesoTara
);

// Agregar documentación aduanera
$cartaPorte->AgregarDocumentacionAduanera(
    '01',                    // TipoDocumento
    '11  11  1111  1111111', // NumPedimento
    '1',                     // IdentDocAduanero
    'XAXX010101000'          // RFCImpo
);

// Agregar guía de identificación
$cartaPorte->AgregarGuiaIdentificacion(
    'GI001',                 // NumeroGuiaIdentificacion
    'Guía de carga',         // DescripGuiaIdentificacion
    '1000.00'                // PesoGuiaIdentificacion
);

// Agregar cantidad transporta
$cartaPorte->AgregarCantidadTransporta(
    '1.00',                  // Cantidad
    'OR001',                 // IDOrigen
    'DE001',                 // IDDestino
    '01'                     // CvesTransporte
);

// Agregar autotransporte
$cartaPorte->AgregarAutotransporte(
    'TPAF01',                // PermSCT
    'PERM001',               // NumPermisoSCT
    'VL',                    // ConfigVehicular
    '2000.00',               // PesoBrutoVehicular
    'ABC123',                // PlacaVM
    '2020',                  // AnioModeloVM
    'Aseguradora Demo',      // AseguraRespCivil
    'POL001',                // PolizaRespCivil
    'Aseguradora Demo',      // AseguraMedAmbiente
    'POL002',                // PolizaMedAmbiente
    'Aseguradora Demo',      // AseguraCarga
    'POL003',                // PolizaCarga
    '1000.00'                // PrimaSeguro
);

// Agregar remolque
$cartaPorte->AgregarRemolque(
    'CTR004',                // SubTipoRem
    'XYZ789'                 // Placa
);

// Agregar figura de transporte
$cartaPorte->AgregarFiguraTransporte(
    '01',                    // TipoFigura
    'XAXX010101000',         // RFCFigura
    'LIC001',                // NumLicencia
    'TRANSPORTISTA DEMO',    // NombreFigura
    'Calle Transporte',      // Calle
    '789',                   // NumeroExterior
    'C',                     // NumeroInterior
    'Transporte',            // Colonia
    'Ciudad Transporte',     // Localidad
    'Cerca de la terminal',  // Referencia
    '003',                   // Municipio
    'DEM',                   // Estado
    'MEX',                   // Pais
    '06500'                  // CodigoPostal
);

// Agregar parte de transporte
$cartaPorte->AgregarParteTransporte('PT01');

// Finalizar Carta Porte
$cartaPorte->FinalizarCartaPorte(
    '1000.00',               // PesoBrutoTotal
    'KGM',                   // UnidadPeso
    '1',                     // NumTotalMercancias
    'Sí',                    // LogisticaInversaRecoleccionDevolucion
    '950.00',                // PesoNetoTotal
    '50.00'                  // CargoPorTasacion
);

// Generar XML
$errores = '';
$resultado = $cartaPorte->CrearCartaPorteXML(
    'usuario_finkok',
    'password_finkok',
    $errores,
    './xml/',
    'carta_porte_demo'
);

if ($resultado) {
    echo "XML de Carta Porte generado exitosamente";
} else {
    echo "Error: " . $errores;
}
?>
```

## 🔧 Configuración

### Credenciales Finkok

Para usar las validaciones de Finkok, necesitas:

1. **Cuenta de Finkok**: Registrarte en [demo-facturacion.finkok.com](https://demo-facturacion.finkok.com)
2. **Credenciales**: Usuario y contraseña de tu cuenta
3. **RFC Activo**: El RFC debe estar activo en tu cuenta

### Estructura de Directorios

```
proyecto/
├── xml/                    # Directorio para archivos XML generados
├── logs/                   # Directorio para logs (opcional)
└── src/                    # Clases del SDK
```

## 📋 Validaciones

### CFDI 4.0
- ✅ Validación de credenciales Finkok
- ✅ Validación de RFC emisor
- ✅ Validación de estructura XML
- ✅ Validación de esquemas SAT

### Recepción de Pagos
- ✅ Validación de complemento Pagos20
- ✅ Validación de documentos relacionados
- ✅ Validación de totales de impuestos
- ✅ Validación de montos de pagos

### Carta Porte
- ✅ Validación de complemento CartaPorte31
- ✅ Validación de ubicaciones
- ✅ Validación de mercancías
- ✅ Validación de figuras de transporte

## 🚨 Manejo de Errores

```php
// Ejemplo de manejo de errores
$errores = '';
$resultado = $cfdi->CrearFacturaXML($user, $pass, $errores);

if (!$resultado) {
    echo "Error en la generación: " . $errores;
    // Manejar el error apropiadamente
}
```

## 📄 Archivos Generados

Los archivos XML se generan en el directorio especificado con la siguiente estructura:

```
xml/
├── factura_demo.xml
├── recepcion_pagos.xml
└── carta_porte_demo.xml
```

## 🔍 Validación de XML

Los XML generados son válidos según los esquemas del SAT:

- **CFDI 4.0**: `http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd`
- **Pagos 2.0**: `http://www.sat.gob.mx/sitio_internet/cfd/Pagos/Pagos20.xsd`
- **Carta Porte 3.1**: `http://www.sat.gob.mx/sitio_internet/cfd/CartaPorte/CartaPorte31.xsd`

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📝 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 📞 Soporte

Para soporte técnico o preguntas sobre el SDK:

- 📧 Email: mejia_alfredo@outlook.com
- 📱 Teléfono: +52 435 101 5709
- 🌐 Sitio web: https://ejemplo.com

## 🔄 Versiones

- **v1.0.0**: Versión inicial con CFDI 4.0
- **v1.1.0**: Agregado soporte para Recepción de Pagos
- **v1.2.0**: Agregado soporte para Carta Porte 3.1
- **v1.3.0**: Mejoras en validaciones y manejo de errores

---

**Nota**: Este SDK está diseñado para uso en ambiente de desarrollo y pruebas. Para uso en producción, asegúrate de seguir las mejores prácticas de seguridad y validación del SAT.
