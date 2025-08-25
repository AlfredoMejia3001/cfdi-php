# 🚀 CFDI PHP - Librería Completa para Comprobantes Fiscales Digitales

## 📋 Descripción

**CFDI PHP** es una librería moderna y escalable para generar Comprobantes Fiscales Digitales por Internet (CFDI) en PHP. Implementa patrones de diseño avanzados para ser mantenible, extensible y fácil de usar.

## ✨ Características Principales

- ✅ **CFDI 4.0**: Generación de facturas electrónicas estándar
- ✅ **Complemento de Pagos 2.0**: Para recibos de pago y abonos
- ✅ **Complemento de Carta Porte 3.1**: Para transporte de mercancías
- ✅ **Complemento de Nómina 1.2**: Para nóminas y recursos humanos
- ✅ **Validación Finkok**: Integración con servicios de validación automática
- ✅ **Arquitectura Moderna**: Builder Pattern, Factory Pattern, Strategy Pattern
- ✅ **XML Válido**: Generación conforme a esquemas del SAT
- ✅ **Manejo de Errores**: Validaciones robustas y mensajes claros

## 🏗️ Arquitectura de la Librería

### 🔧 Patrones de Diseño Implementados

1. **Builder Pattern**: Construcción fluida y expresiva de CFDIs
2. **Factory Pattern**: Creación centralizada de diferentes tipos
3. **Strategy Pattern**: Complementos pluggeables y extensibles
4. **Service Layer**: Servicios especializados y reutilizables
5. **Dependency Injection**: Flexibilidad y testing mejorado

### 📁 Estructura del Proyecto

```
cfdi-php/
├── src/
│   ├── CFDIFactory.php              # 🎯 Punto de entrada principal
│   ├── Builders/                    # 🔧 Constructores fluidos
│   │   ├── AbstractCFDIBuilder.php
│   │   ├── CFDI40Builder.php
│   │   ├── PagosBuilder.php
│   │   ├── CartaPorteBuilder.php
│   │   └── NominaBuilder.php
│   ├── Services/                    # 🛠️ Servicios especializados
│   │   ├── FinkokService.php
│   │   ├── ValidationService.php
│   │   ├── XMLGeneratorService.php
│   │   └── FileService.php
│   ├── Models/                      # 📋 Modelos de datos
│   │   ├── CFDIData.php
│   │   ├── Comprobante.php
│   │   ├── Emisor.php
│   │   └── Receptor.php
│   ├── Contracts/                   # 📜 Interfaces
│   │   ├── CFDIBuilderInterface.php
│   │   ├── ValidationServiceInterface.php
│   │   └── ComplementStrategyInterface.php
│   ├── Strategies/                  # 🔌 Complementos pluggeables
│   │   ├── Pagos/
│   │   ├── CartaPorte/
│   │   ├── Nomina/
│   │   └── ComercioExterior/
│   ├── Exceptions/                  # ⚠️ Excepciones personalizadas
│   │   ├── CFDIException.php
│   │   └── ValidationException.php
│   └── Legacy/                      # 🔄 Clases originales (compatibilidad)
│       ├── CFDI40.php
│       ├── CartaPorte31.php
│       ├── Nomina12.php
│       └── RecepcionPagos.php
├── examples/                        # 📚 Ejemplos de uso
│   ├── ejemplo_cfdi_basico.php
│   ├── ejemplo_pagos.php
│   ├── ejemplo_cartaporte.php
│   ├── ejemplo_nomina.php
│   └── README.md
├── composer.json                    # 📦 Dependencias
└── README.md                        # 📖 Esta documentación
```

## 🚀 Instalación

### Requisitos Previos

- **PHP 7.4 o superior**
- **Composer** instalado
- **Extensiones PHP**:
  - `ext-soap` (para comunicación con Finkok)
  - `ext-dom` (para generación de XML)
  - `ext-libxml` (para manipulación de XML)

### Instalación

1. **Clonar el repositorio:**
```bash
git clone https://github.com/AlfredoMejia/cfdi-php.git
cd cfdi-php
```

2. **Instalar dependencias:**
```bash
composer install
```

3. **Verificar extensiones:**
```bash
php -m | grep -E "(soap|dom)"
```

## 🔧 Configuración

### Credenciales de Finkok

Para usar las validaciones automáticas, necesitas una cuenta en Finkok:

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

## 📖 Guía de Uso

### 🎯 Punto de Entrada Principal

La librería se usa a través de `CFDIFactory`, que es el punto de entrada principal:

```php
<?php
require_once 'vendor/autoload.php';

use AlfredoMejia\CfdiPhp\CFDIFactory;

$factory = new CFDIFactory();
```

### 🧾 1. CFDI Básico 4.0

#### Uso Básico
```php
$cfdi = $factory->createCFDI40Builder()
    ->comprobante(
        noCertificado: '30001000000500003416',
        serie: 'A',
        folio: '12345',
        fecha: '2024-01-15T10:30:00',
        subTotal: '1000.00',
        total: '1160.00',
        lugarExpedicion: '06500'
    )
    ->emisor(
        rfc: 'XAXX010101000',
        nombre: 'EMPRESA DEMO SA DE CV',
        regimenFiscal: '601'
    )
    ->receptor(
        rfc: 'XEXX010101000',
        nombre: 'CLIENTE DEMO SA DE CV',
        regimenFiscalReceptor: '601',
        usoCFDI: 'G01',
        domicilioFiscalReceptor: '06500'
    )
    ->concepto(
        claveProdServ: '84111506',
        cantidad: '1.00',
        claveUnidad: 'H87',
        descripcion: 'Servicio de consultoría',
        valorUnitario: '1000.00',
        importe: '1000.00',
        objetoImp: '02'
    )
    ->impuestosTotales()
    ->trasladoTotal(
        base: '1000.00',
        impuesto: '002',
        tipoFactor: 'Tasa',
        tasaOCuota: '0.160000',
        importe: '160.00'
    )
    ->build();
```

#### Procesamiento Completo
```php
$result = $factory->processCFDI(
    cfdiData: $cfdi,
    finkokUser: $finkokUser,
    finkokPassword: $finkokPass,
    filePath: './output/',
    fileName: 'FACTURA_001'
);

if ($result->isSuccess()) {
    echo "✅ CFDI generado exitosamente\n";
    echo "📄 Archivo: " . $result->getFilePath() . "\n";
} else {
    echo "❌ Error: " . implode(', ', $result->getErrors()) . "\n";
}
```

### 💰 2. Complemento de Pagos 2.0

#### Uso del Builder de Pagos
```php
$cfdi = $factory->createPagos()
    ->comprobante(
        noCertificado: '30001000000500003416',
        serie: 'P',
        folio: '001',
        fecha: '2024-01-15T10:30:00',
        subTotal: '0.00',
        total: '0.00',
        lugarExpedicion: '06500',
        tipoDeComprobante: 'P'  // Importante: P = Pago
    )
    ->emisor(
        rfc: 'XAXX010101000',
        nombre: 'EMPRESA PAGOS SA DE CV',
        regimenFiscal: '601'
    )
    ->receptor(
        rfc: 'XEXX010101000',
        nombre: 'CLIENTE PAGOS SA DE CV',
        regimenFiscalReceptor: '605',
        usoCFDI: 'CP01',
        domicilioFiscalReceptor: '06500'
    )
    ->concepto(
        claveProdServ: '84111506',
        cantidad: '1.00',
        claveUnidad: 'E48',
        descripcion: 'Pago por servicios',
        valorUnitario: '0.00',  // Puede ser 0.00 para tipo P
        importe: '0.00',        // Puede ser 0.00 para tipo P
        objetoImp: '01'
    )
    ->pago(
        fechaPago: '2024-01-15',
        formaDePagoP: '01',
        monto: '1000.00',
        monedaP: 'MXN',
        tipoCambioP: '1.00'
    )
    ->doctoRelacionado(
        idDocumento: 'uuid-del-documento',
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
    ->build();
```

### 🚛 3. Complemento de Carta Porte 3.1

#### Uso del Builder de Carta Porte
```php
$cfdi = $factory->createCartaPorte()
    ->comprobante(
        noCertificado: '30001000000500003416',
        serie: 'CP',
        folio: '001',
        fecha: '2024-01-15T10:30:00',
        subTotal: '1000.00',
        total: '1160.00',
        lugarExpedicion: '06500'
    )
    ->emisor(
        rfc: 'XAXX010101000',
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
    ->impuestosTotales()
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
        rfcRemitente: 'XAXX010101000',
        fechaHora: '2024-01-15T08:00:00',
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
        fechaHora: '2024-01-15T18:00:00',
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
```

### 👥 4. Complemento de Nómina 1.2

#### Uso del Builder de Nómina
```php
$cfdi = $factory->createNomina()
    ->comprobante(
        noCertificado: '30001000000500003416',
        serie: 'N',
        folio: '001',
        fecha: '2024-01-15T08:00:00',
        subTotal: '5000.00',
        total: '5000.00',
        lugarExpedicion: '06500'
    )
    ->emisor(
        rfc: 'XAXX010101000',
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
        valorUnitario: '5000.00',
        importe: '5000.00',
        objetoImp: '01'
    )
    ->tipoNomina('O')
    ->fechasPago(
        fechaPago: '2024-01-15',
        fechaInicialPago: '2024-01-01',
        fechaFinalPago: '2024-01-15',
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
        'antiguedad' => 'P4Y',
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
        'salarioBaseCotApor' => '5000.00',
        'salarioDiarioIntegrado' => '333.33'
    ])
    ->percepciones([
        'totalSueldos' => '5000.00',
        'totalSeparacionIndemnizacion' => '0.00',
        'totalJubilacionPensionRetiro' => '0.00',
        'totalGravado' => '5000.00',
        'totalExento' => '0.00'
    ])
    ->deducciones([
        'totalOtrasDeducciones' => '0.00',
        'totalImpuestosRetenidos' => '0.00'
    ])
    ->build();
```

## 🔍 Servicios Disponibles

### 1. Validación de Credenciales
```php
$credentialsResult = $factory->validateCredentials($finkokUser, $finkokPass);

if ($credentialsResult->isValid()) {
    echo "✅ Credenciales válidas\n";
} else {
    echo "❌ Credenciales inválidas: " . implode(', ', $credentialsResult->getErrors()) . "\n";
}
```

### 2. Validación de RFC
```php
$rfcResult = $factory->validateRFC($finkokUser, $finkokPass, 'XAXX010101000');

if ($rfcResult->isValid()) {
    echo "✅ RFC válido y activo\n";
} else {
    echo "❌ RFC inválido: " . implode(', ', $rfcResult->getErrors()) . "\n";
}
```

### 3. Generación de XML Solo
```php
$xml = $factory->generateXML($cfdiData);
echo "XML generado: " . $xml;
```

### 4. Guardado de Archivo Solo
```php
$path = $factory->saveXML($xml, './output/', 'mi_cfdi');
echo "Archivo guardado en: " . $path;
```

## 📊 Tipos de Comprobante

| Tipo | Descripción | Uso |
|------|-------------|-----|
| `I` | Ingreso | Facturas de venta |
| `E` | Egreso | Notas de crédito |
| `T` | Traslado | Transporte de mercancías |
| `N` | Nómina | Pagos de nómina |
| `P` | Pago | Recibos de pago |

## 🔧 Configuración Avanzada

### Múltiples Conceptos
```php
$cfdi = $factory->createCFDI40Builder()
    ->comprobante(/* ... */)
    ->emisor(/* ... */)
    ->receptor(/* ... */)
    // Primer concepto
    ->concepto(
        claveProdServ: '84111506',
        cantidad: '2.00',
        claveUnidad: 'H87',
        descripcion: 'Consultoría Básica',
        valorUnitario: '500.00',
        importe: '1000.00',
        objetoImp: '02'
    )
    ->traslado('1000.00', '002', 'Tasa', '0.160000', '160.00')
    // Segundo concepto
    ->concepto(
        claveProdServ: '84111507',
        cantidad: '1.00',
        claveUnidad: 'H87',
        descripcion: 'Consultoría Especializada',
        valorUnitario: '1500.00',
        importe: '1500.00',
        objetoImp: '02'
    )
    ->traslado('1500.00', '002', 'Tasa', '0.160000', '240.00')
    // Impuestos totales
    ->impuestosTotales(null, '400.00')
    ->trasladoTotal('2500.00', '002', 'Tasa', '0.160000', '400.00')
    ->build();
```

### Impuestos por Concepto
```php
$cfdi = $factory->createCFDI40Builder()
    ->comprobante(/* ... */)
    ->emisor(/* ... */)
    ->receptor(/* ... */)
    ->concepto(/* ... */)
    ->traslado('1000.00', '002', 'Tasa', '0.160000', '160.00')  // IVA
    ->retencion('1000.00', '001', 'Tasa', '0.100000', '100.00') // ISR
    ->build();
```

## 📁 Ejemplos de Uso

### Ejecutar Ejemplos
```bash
# CFDI básico
php examples/ejemplo_cfdi_basico.php

# Complemento de Pagos
php examples/ejemplo_pagos.php

# Complemento de Carta Porte
php examples/ejemplo_cartaporte.php

# Complemento de Nómina
php examples/ejemplo_nomina.php
```

### Archivos Generados
Todos los ejemplos generan archivos XML en la carpeta `examples/output/`:
- `CFDI_BASICO.xml`
- `CFDI_PAGOS.xml`
- `CFDI_CARTA_PORTE.xml`
- `CFDI_NOMINA.xml`

## 🔍 Validaciones y Errores

### Validaciones Automáticas
1. **Credenciales Finkok**: Verificación de usuario y contraseña
2. **RFC Emisor**: Estado activo en la cuenta
3. **Datos CFDI**: Validación de campos requeridos
4. **Complementos**: Validación específica por tipo

### Manejo de Errores
```php
$result = $factory->processCFDI(/* ... */);

if (!$result->isSuccess()) {
    echo "❌ Error: " . $result->getMessage() . "\n";
    
    if ($result->getErrors()) {
        echo "🔍 Errores detallados:\n";
        foreach ($result->getErrors() as $error) {
            echo "  - $error\n";
        }
    }
}
```

### Errores Comunes

#### Error de Credenciales
```
❌ Error: Credenciales inválidas
```
**Solución**: Verificar credenciales de Finkok.

#### RFC Inactivo
```
❌ Error: El RFC emisor se encuentra inactivo en la cuenta
```
**Solución**: Activar RFC en cuenta de Finkok.

#### Error de Validación
```
❌ Error: El importe debe ser mayor a cero en el concepto 1
```
**Solución**: Para tipo de comprobante 'P' (Pago), el importe puede ser 0.00.



## 📞 Soporte

### Recursos de Ayuda

- **Email**: alfredo.mejia@finkok.com
- **Documentación**: Este README y ejemplos en `/examples/`

### Recursos Externos
- **SAT**: [www.sat.gob.mx](https://www.sat.gob.mx)
- **Finkok**: [www.finkok.com](https://www.finkok.com)
- **GitHub**: Repositorio del proyecto

## 🔄 Historial de Versiones

- **v1.0.0**: CFDI 4.0 básico
- **v1.1.0**: Recepción de Pagos
- **v1.2.0**: Carta Porte 3.1
- **v1.3.0**: Nómina 1.2
- **v1.4.0**: Nueva arquitectura escalable
- **v2.0.0**: Arquitectura moderna con patrones de diseño

## 📝 Notas Importantes

1. **Credenciales**: Siempre usa credenciales válidas de Finkok
2. **Validación**: Todas las clases incluyen validaciones automáticas
3. **XML**: Los archivos se generan conforme a esquemas del SAT

