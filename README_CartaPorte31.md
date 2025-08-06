# CartaPorte31 - Complemento de Carta Porte versión 3.1

Esta clase permite generar el complemento de Carta Porte versión 3.1 para CFDI con validaciones de Finkok.

## ⚠️ Importante: Estructura del XML

La clase `CartaPorte31` ahora incluye **todos los atributos del CFDI principal** (como `CFDI40.php`) y genera un **CFDI completo** con el complemento de Carta Porte integrado, siguiendo el mismo patrón que `RecepcionPagos.php`.

### Estructura del XML generado:

```xml
<cfdi:Comprobante xmlns:cartaporte31="http://www.sat.gob.mx/CartaPorte31" ...>
    <cfdi:Emisor .../>
    <cfdi:Receptor .../>
    <cfdi:Conceptos>
        <cfdi:Concepto ...>
            <!-- Conceptos del CFDI -->
        </cfdi:Concepto>
    </cfdi:Conceptos>
    <cfdi:Impuestos .../>
    <cfdi:Complemento>
        <cartaporte31:CartaPorte Version="3.1" ...>
            <!-- Aquí va el contenido de Carta Porte -->
        </cartaporte31:CartaPorte>
    </cfdi:Complemento>
</cfdi:Comprobante>
```

## Características

- ✅ Generación de XML válido según esquema XSD de Carta Porte 3.1
- ✅ Validaciones de credenciales de Finkok
- ✅ Soporte para todos los tipos de transporte (Autotransporte, Marítimo, Aéreo, Ferroviario)
- ✅ Manejo de ubicaciones, mercancías y regímenes aduaneros
- ✅ Logging detallado para debugging
- ✅ Uso de DOMDocument para mejor rendimiento y validación
- ✅ Atributos requeridos y opcionales según XSD
- ✅ Soporte completo para mercancías con atributos COFEPRIS
- ✅ Soporte para figuras de transporte con domicilios
- ✅ Soporte para partes de transporte
- ✅ Soporte para todos los atributos del XML de ejemplo

## Requisitos

- PHP 7.4 o superior
- Extensión SOAP habilitada
- Credenciales válidas de Finkok

## Instalación

1. Incluye el archivo `CartaPorte31.php` en tu proyecto
2. Asegúrate de tener las credenciales de Finkok

```php
require_once 'src/CartaPorte31.php';
```

## Uso Básico

### 1. Crear instancia y configurar CFDI principal

```php
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
    "42501" // LugarExpedicion
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

// Agregar concepto
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
```

### 2. Agregar regímenes aduaneros (opcional)

```php
$cartaPorte->AgregarRegimenAduanero("01");
$cartaPorte->AgregarRegimenAduanero("02");
$cartaPorte->AgregarRegimenesAduaneros();
```

### 3. Agregar ubicaciones

```php
// Ubicación de origen
$cartaPorte->AgregarUbicacion(
    "Origen", // TipoUbicacion
    "XAXX010101000", // RFCRemitenteDestinatario
    "2024-01-15T08:00:00", // FechaHoraSalidaLlegada
    "OR000001", // IDUbicacion
    "EMPRESA ORIGEN SA DE CV" // NombreRemitenteDestinatario
);

// Agregar domicilio
$cartaPorte->AgregarDomicilio(
    "Av. Principal", // Calle
    "123", // NumeroExterior
    "A", // NumeroInterior
    "Centro", // Colonia
    "Ciudad de México", // Localidad
    "Frente al parque", // Referencia
    "Cuauhtémoc", // Municipio
    "Ciudad de México", // Estado
    "MEX", // Pais
    "06000" // CodigoPostal
);
```

### 4. Agregar mercancías

```php
$cartaPorte->AgregarMercancia(
    "10101501", // BienesTransp
    "Productos electrónicos", // Descripcion
    "10.000000", // Cantidad
    "H87", // ClaveUnidad
    "Pieza", // Unidad
    "30/40/30cm", // Dimensiones
    "No", // MaterialPeligroso
    null, // CveMaterialPeligroso
    null, // Embalaje
    null, // DescripEmbalaje
    "50.000", // PesoEnKg
    "50000.00", // ValorMercancia
    "MXN" // Moneda
);

// Agregar detalle de mercancía
$cartaPorte->AgregarDetalleMercancia(
    "KGM", // UnidadPesoMerc
    "100.000", // PesoBruto
    "50.000", // PesoNeto
    "50.000", // PesoTara
    10 // NumPiezas
);
```

### 5. Agregar tipo de transporte

#### Autotransporte
```php
$cartaPorte->AgregarAutotransporte(
    "TPAF01", // PermSCT
    "123456789", // NumPermisoSCT
    "T3", // ConfigVehicular
    "35.50", // PesoBrutoVehicular
    "ABC123", // PlacaVM
    2020, // AnioModeloVM
    "Seguros ABC", // AseguraRespCivil
    "POL123456" // PolizaRespCivil
);

// Agregar remolque
$cartaPorte->AgregarRemolque(
    "C2", // SubTipoRem
    "XYZ789" // Placa
);
```

#### Transporte Marítimo
```php
$cartaPorte->AgregarTransporteMaritimo(
    "CEM", // TipoEmbarcacion
    "MEX123456", // Matricula
    "IMO1234567", // NumeroOMI
    "MEX", // NacionalidadEmbarc
    "5000.000", // UnidadesDeArqBruto
    "Carga General", // TipoCarga
    null, // PermSCT
    null, // NumPermisoSCT
    "Seguros Marítimos", // NombreAseg
    "POL789012", // NumPolizaSeguro
    2018, // AnioEmbarcacion
    "Buque Carga", // NombreEmbarc
    null, // Eslora
    null, // Manga
    null, // Calado
    null, // Puntal
    "Línea Naviera", // LineaNaviera
    "Agente Naviero SA", // NombreAgenteNaviero
    "AUT123456" // NumAutorizacionNaviero
);

// Agregar contenedor
$cartaPorte->AgregarContenedor(
    "DM", // TipoContenedor
    "ABCD1234567", // MatriculaContenedor
    "PREC123456" // NumPrecinto
);
```

#### Transporte Aéreo
```php
$cartaPorte->AgregarTransporteAereo(
    "TPXA01", // PermSCT
    "AUT789012", // NumPermisoSCT
    "AWB123456789", // NumeroGuia
    "001", // CodigoTransportista
    "XA-ABC", // MatriculaAeronave
    "Seguros Aéreos", // NombreAseg
    "POL345678" // NumPolizaSeguro
);
```

#### Transporte Ferroviario
```php
$cartaPorte->AgregarTransporteFerroviario(
    "Tren de Carga", // TipoDeServicio
    "Interlineal", // TipoDeTrafico
    "Seguros Ferroviarios", // NombreAseg
    "POL901234" // NumPolizaSeguro
);

// Agregar derechos de paso
$cartaPorte->AgregarDerechosDePaso(
    "Cubierto", // TipoDerechoDePaso
    "50.00" // KilometrajePagado
);

// Agregar carro
$cartaPorte->AgregarCarro(
    "Plataforma", // TipoCarro
    "CARR123456", // MatriculaCarro
    "GUIA789012", // GuiaCarro
    "25.000" // ToneladasNetasCarro
);
```

### 6. Finalizar y crear XML

```php
// Finalizar Carta Porte
$cartaPorte->FinalizarCartaPorte("100.000");

// Crear XML con validaciones de Finkok
$errores = "";
$errorE = "";

$xmlResult = $cartaPorte->CrearCartaPorteXML(
    $finkokUser,
    $finkokPass,
    $errores,
    "./output", // Ruta donde guardar
    "CARTA_PORTE_EJEMPLO", // Nombre del archivo
    $errorE
);

if ($xmlResult !== false) {
    echo "✅ XML creado exitosamente";
} else {
    echo "❌ Error: " . $errores;
}
```

## Validaciones de Finkok

La clase incluye validaciones automáticas de credenciales de Finkok:

1. **Validación de credenciales**: Verifica que el usuario y contraseña sean válidos
2. **Logging detallado**: Registra todos los pasos del proceso en `cfdi_debug.log`
3. **Manejo de errores**: Proporciona mensajes de error claros

### Archivo de Log

Todos los eventos se registran en `cfdi_debug.log`:

```
[2024-01-15 10:30:00] === INICIO DE VALIDACIÓN DE CREDENCIALES ===
[2024-01-15 10:30:01] Validando credenciales con Finkok Utilities
[2024-01-15 10:30:02] Cliente SOAP creado correctamente
[2024-01-15 10:30:03] Validación exitosa
```

## Métodos Principales

### Configuración
- `CartaPorte31()` - Configura los atributos principales
- `AgregarRegimenAduanero()` - Agrega regímenes aduaneros
- `AgregarRegimenesAduaneros()` - Finaliza la sección de regímenes

### Ubicaciones
- `AgregarUbicacion()` - Agrega ubicaciones de origen/destino
- `AgregarDomicilio()` - Agrega información de domicilio

### Mercancías
- `AgregarMercancia()` - Agrega información de mercancías
- `AgregarDetalleMercancia()` - Agrega detalles específicos
- `AgregarDocumentacionAduanera()` - Agrega documentación aduanera
- `AgregarGuiaIdentificacion()` - Agrega guías de identificación
- `AgregarCantidadTransporta()` - Agrega cantidades transportadas

### Tipos de Transporte
- `AgregarAutotransporte()` - Configura autotransporte
- `AgregarRemolque()` - Agrega remolques
- `AgregarTransporteMaritimo()` - Configura transporte marítimo
- `AgregarContenedor()` - Agrega contenedores marítimos
- `AgregarTransporteAereo()` - Configura transporte aéreo
- `AgregarTransporteFerroviario()` - Configura transporte ferroviario
- `AgregarDerechosDePaso()` - Agrega derechos de paso
- `AgregarCarro()` - Agrega carros ferroviarios

### Generación de XML
- `CrearXML()` - Genera XML sin validaciones (solo desarrollo)
- `CrearCartaPorteXML()` - Genera XML con validaciones de Finkok

## Ejemplos de Uso

### 1. Solo Complemento Carta Porte
Ver el archivo `ejemplo_carta_porte.php` para generar solo el complemento.

### 2. CFDI Completo con Carta Porte
Ver el archivo `ejemplo_cfdi_con_cartaporte.php` para integrar el complemento dentro de un CFDI completo.

### Integración con CFDI40

Para integrar la Carta Porte en un CFDI completo:

```php
// Crear CFDI principal
$cfdi = new CFDI40();
$cfdi->CFDI40(/* parámetros */);
$cfdi->AgregarEmisor(/* parámetros */);
$cfdi->AgregarReceptor(/* parámetros */);
$cfdi->AgregarConcepto(/* parámetros */);

// Crear complemento Carta Porte
$cartaPorte = new CartaPorte31();
$cartaPorte->CartaPorte31(/* parámetros */);
$cartaPorte->AgregarUbicacion(/* parámetros */);
$cartaPorte->AgregarMercancia(/* parámetros */);
$cartaPorte->FinalizarCartaPorte(/* parámetros */);

// Obtener XML del complemento
$xmlCartaPorte = $cartaPorte->CrearXML();

// Crear CFDI completo con validaciones
$xmlResult = $cfdi->CrearFacturaXML(
    $finkokUser,
    $finkokPass,
    $errores,
    "./output",
    "CFDI_CON_CARTA_PORTE",
    $errorE
);
```

## Notas Importantes

1. **Credenciales de Finkok**: Debes tener credenciales válidas de Finkok
2. **Extensión SOAP**: Asegúrate de que la extensión SOAP esté habilitada
3. **Permisos de escritura**: El directorio de salida debe tener permisos de escritura
4. **Logs**: Revisa `cfdi_debug.log` para debugging

## Soporte

Para reportar problemas o solicitar nuevas características, contacta al equipo de desarrollo. 