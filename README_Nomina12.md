# CFDI con Complemento de Nómina 1.2

Este módulo permite generar CFDI con el complemento de Nómina versión 1.2 según las especificaciones del SAT.

## Características

- ✅ Generación de CFDI 4.0 con complemento de Nómina 1.2
- ✅ Validación de credenciales de Finkok
- ✅ Validación de RFC emisor
- ✅ Soporte para todos los elementos de nómina:
  - Percepciones (incluyendo horas extra, acciones/títulos)
  - Deducciones
  - Otros pagos (subsidios, compensaciones)
  - Incapacidades
  - Jubilación/Pensión/Retiro
  - Separación/Indemnización
- ✅ Generación de XML completo o solo complemento
- ✅ Manejo de errores detallado

## Archivos de Ejemplo

### 1. `ejemplo_nomina12_basico.php`
Ejemplo con elementos mínimos necesarios para generar una nómina básica.

### 2. `ejemplo_nomina12_completo.php`
Ejemplo completo con todos los elementos opcionales de nómina.

## Uso Básico

```php
<?php
require_once 'src/Nomina12.php';

// Crear instancia
$nomina = new Nomina12();

// Configurar CFDI principal
$nomina->CFDI40(
    "30001000000500003416", // NoCertificado
    "NOM", // Serie
    "001", // Folio
    "2025-01-15T08:00:00", // Fecha
    "5000.00", // SubTotal
    "MXN", // Moneda
    "5000.00", // Total
    "N", // TipoDeComprobante (N = Nómina)
    "01", // Exportacion
    "PUE", // MetodoPago
    "03", // FormaPago
    "42501" // LugarExpedicion
);

// Agregar emisor y receptor
$nomina->AgregarEmisor("EKU9003173C9", "EMPRESA EJEMPLO", "601");
$nomina->AgregarReceptor("XAXX010101000", "JUAN PEREZ", "616", "CN01", "80290");

// Agregar concepto
$nomina->AgregarConcepto(
    "84111506", // ClaveProdServ
    "NOM001", // NoIdentificacion
    "1", // Cantidad
    "ACT", // ClaveUnidad
    "Servicio", // Unidad
    "Pago de nómina", // Descripcion
    "5000.00", // ValorUnitario
    "5000.00", // Importe
    "01" // ObjetoImp
);

// Configurar nómina
$nomina->Nomina12(
    "1.2", // Version
    "O", // TipoNomina (O = Ordinaria)
    "2025-01-15T08:00:00", // FechaPago
    "2025-01-01T00:00:00", // FechaInicialPago
    "2025-01-15T23:59:59", // FechaFinalPago
    "15" // NumDiasPagados
);

// Agregar receptor de nómina
$nomina->AgregarReceptorNomina(
    "PEGJ800101HDFXXX01", // Curp
    null, // NumSeguridadSocial
    null, // FechaInicioRelLaboral
    null, // Antigüedad
    "01", // TipoContrato
    null, // Sindicalizado
    null, // TipoJornada
    "02", // TipoRegimen
    "EMP001", // NumEmpleado
    null, // Departamento
    null, // Puesto
    null, // RiesgoPuesto
    "04", // PeriodicidadPago
    null, // Banco
    null, // CuentaBancaria
    null, // SalarioBaseCotApor
    null, // SalarioDiarioIntegrado
    "MEX" // ClaveEntFed
);

// Agregar percepción
$nomina->AgregarPercepcion(
    "001", // TipoPercepcion
    "001", // Clave
    "Sueldo ordinario", // Concepto
    "5000.00", // ImporteGravado
    "0.00" // ImporteExento
);

// Finalizar nómina
$nomina->FinalizarNomina(
    "5000.00", // TotalSueldos
    null, // TotalSeparacionIndemnizacion
    null, // TotalJubilacionPensionRetiro
    "5000.00", // TotalGravado
    "0.00", // TotalExento
    null, // TotalOtrasDeducciones
    null // TotalImpuestosRetenidos
);

// Generar XML
$errores = "";
$resultado = $nomina->CrearNominaXML(
    "tu_usuario@finkok.com.mx",
    "tu_password",
    $errores,
    "./",
    "NOMINA_EJEMPLO"
);

if ($resultado === true) {
    echo "XML generado exitosamente";
} else {
    echo "Error: " . $errores;
}
```

## Elementos de Nómina

### Percepciones

```php
// Percepción básica
$nomina->AgregarPercepcion(
    "001", // TipoPercepcion
    "001", // Clave
    "Sueldo ordinario", // Concepto
    "5000.00", // ImporteGravado
    "0.00" // ImporteExento
);

// Percepción con horas extra
$horasExtra = $nomina->AgregarHorasExtra(
    "2", // Dias
    "01", // TipoHoras (01 = Dobles)
    "8", // HorasExtra
    "800.00" // ImportePagado
);

$nomina->AgregarPercepcion(
    "004", // TipoPercepcion
    "004", // Clave
    "Horas extra", // Concepto
    "800.00", // ImporteGravado
    "0.00", // ImporteExento
    null, // AccionesOTitulos
    $horasExtra // HorasExtra
);
```

### Deducciones

```php
$nomina->AgregarDeduccion(
    "001", // TipoDeduccion (001 = Seguridad social)
    "001", // Clave
    "IMSS", // Concepto
    "600.00" // Importe
);
```

### Otros Pagos

```php
// Otro pago con subsidio
$subsidio = $nomina->AgregarSubsidioAlEmpleo("100.00");
$nomina->AgregarOtroPago(
    "002", // TipoOtroPago
    "002", // Clave
    "Subsidio empleo", // Concepto
    "100.00", // Importe
    $subsidio // SubsidioAlEmpleo
);

// Otro pago con compensación
$compensacion = $nomina->AgregarCompensacionSaldosAFavor(
    "500.00", // SaldoAFavor
    "2024", // Año
    "300.00" // RemanenteSalFav
);

$nomina->AgregarOtroPago(
    "003", // TipoOtroPago
    "003", // Clave
    "Compensación saldos a favor", // Concepto
    "200.00", // Importe
    null, // SubsidioAlEmpleo
    $compensacion // CompensacionSaldosAFavor
);
```

### Jubilación/Pensión/Retiro

```php
$nomina->AgregarJubilacionPensionRetiro(
    "100000.00", // TotalUnaExhibicion
    null, // TotalParcialidad
    "1000.00", // MontoDiario
    "80000.00", // IngresoAcumulable
    "20000.00" // IngresoNoAcumulable
);
```

### Separación/Indemnización

```php
$nomina->AgregarSeparacionIndemnizacion(
    "50000.00", // TotalPagado
    "5", // NumAñosServicio
    "8000.00", // UltimoSueldoMensOrd
    "40000.00", // IngresoAcumulable
    "10000.00" // IngresoNoAcumulable
);
```

### Incapacidades

```php
$nomina->AgregarIncapacidad(
    "1", // DiasIncapacidad
    "01", // TipoIncapacidad (01 = Riesgo de trabajo)
    "100.00" // ImporteMonetario
);
```

## Validaciones

### Credenciales de Finkok

El sistema valida las credenciales de Finkok antes de generar el XML:

1. **Validación de autenticación**: Verifica que las credenciales sean válidas
2. **Validación de RFC**: Verifica que el RFC emisor esté activo en la cuenta
3. **Estado del RFC**: Confirma que el RFC tenga estado activo (A)

### Elementos Obligatorios

- CFDI principal con tipo de comprobante "N"
- Al menos una percepción
- Receptor de nómina con elementos obligatorios
- Totales correctos en `FinalizarNomina()`

## Códigos de Catálogos

### Tipo de Nómina
- `O`: Ordinaria
- `E`: Extraordinaria

### Tipo de Contrato
- `01`: Indefinido
- `02`: Obra determinada
- `03`: Tiempo determinado
- `04`: Por temporada
- `05`: Por capacitación inicial
- `06`: A prueba
- `07`: A prueba con opción a planta
- `08`: Por honorarios asimilados a salarios
- `09`: Por comisión
- `10`: Por destajo
- `11`: Por obra determinada
- `12`: Por tiempo indeterminado
- `13`: Por obra determinada con opción a planta

### Periodicidad de Pago
- `01`: Diario
- `02`: Semanal
- `03`: Catorcenal
- `04`: Quincenal
- `05`: Mensual
- `06`: Bimestral
- `07`: Unidad obra
- `08`: Comisión
- `09`: Precio alzado
- `10`: Decenal
- `99`: Otra periodicidad

### Tipo de Percepción
- `001`: Sueldos, Salarios Rayas y Jornales
- `002`: Gratificación Anual (Aguinaldo)
- `003`: Participación de los Trabajadores en las Utilidades PTU
- `004`: Reembolso de Gastos Médicos Dentales y Funerales
- `005`: Fondo de Ahorro
- `006`: Vale de Despensa
- `007`: Vale de Gasolina
- `008`: Vale de Restaurante
- `009`: Vale de Ropa de Trabajo
- `010`: Ayuda para Renta
- `011`: Ayuda para Artículos Escolares
- `012`: Ayuda para Anteojos
- `013`: Ayuda para Transporte
- `014`: Ayuda para Gastos de Funeral
- `015`: Otros Ingresos por Salarios
- `019`: Horas Extra
- `020`: Prima Vacacional
- `021`: Prima de Antigüedad
- `022`: Pagos por Separación
- `023`: Seguros de Gastos Médicos
- `024`: Cuotas Sindicales Patronales
- `025`: Aportaciones Voluntarias
- `026`: Ajuste en Gratificación Anual (Aguinaldo) Exento
- `027`: Ajuste en Gratificación Anual (Aguinaldo) Gravado
- `028`: Ajuste en Participación de los Trabajadores en las Utilidades PTU Exento
- `029`: Ajuste en Participación de los Trabajadores en las Utilidades PTU Gravado
- `030`: Ajuste en Reembolso de Gastos Médicos Dentales y Funerales Exento
- `031`: Ajuste en Reembolso de Gastos Médicos Dentales y Funerales Gravado
- `032`: Ajuste en Fondo de Ahorro Exento
- `033`: Ajuste en Fondo de Ahorro Gravado
- `034`: Ajuste en Vale de Despensa Exento
- `035`: Ajuste en Vale de Despensa Gravado
- `036`: Ajuste en Vale de Gasolina Exento
- `037`: Ajuste en Vale de Gasolina Gravado
- `038`: Ajuste en Vale de Restaurante Exento
- `039`: Ajuste en Vale de Restaurante Gravado
- `040`: Ajuste en Vale de Ropa de Trabajo Exento
- `041`: Ajuste en Vale de Ropa de Trabajo Gravado
- `042`: Ajuste en Ayuda para Renta Exento
- `043`: Ajuste en Ayuda para Renta Gravado
- `044`: Ajuste en Ayuda para Artículos Escolares Exento
- `045`: Ajuste en Ayuda para Artículos Escolares Gravado
- `046`: Ajuste en Ayuda para Anteojos Exento
- `047`: Ajuste en Ayuda para Anteojos Gravado
- `048`: Ajuste en Ayuda para Transporte Exento
- `049`: Ajuste en Ayuda para Transporte Gravado
- `050`: Ajuste en Ayuda para Gastos de Funeral Exento
- `051`: Ajuste en Ayuda para Gastos de Funeral Gravado
- `052`: Ajuste en Otros Ingresos por Salarios Exento
- `053`: Ajuste en Otros Ingresos por Salarios Gravado
- `054`: Ajuste en Prima Vacacional Exento
- `055`: Ajuste en Prima Vacacional Gravado
- `056`: Ajuste en Prima de Antigüedad Exento
- `057`: Ajuste en Prima de Antigüedad Gravado
- `058`: Ajuste en Pagos por Separación Exento
- `059`: Ajuste en Pagos por Separación Gravado
- `060`: Ajuste en Seguros de Gastos Médicos Exento
- `061`: Ajuste en Seguros de Gastos Médicos Gravado
- `062`: Ajuste en Cuotas Sindicales Patronales Exento
- `063`: Ajuste en Cuotas Sindicales Patronales Gravado
- `064`: Ajuste en Aportaciones Voluntarias Exento
- `065`: Ajuste en Aportaciones Voluntarias Gravado
- `066`: Ajuste en Horas Extra Exento
- `067`: Ajuste en Horas Extra Gravado

### Tipo de Deducción
- `001`: Seguridad social
- `002`: ISR
- `003`: Aportaciones a retiro, cesantía y vejez
- `004`: Otros
- `005`: Aportaciones a Fondo de Vivienda
- `006`: Descuento por incapacidad
- `007`: Pensión Alimenticia
- `008`: Renta
- `009`: Préstamos provenientes del Fondo Nacional de la Vivienda para los Trabajadores
- `010`: Pago por crédito de vivienda
- `011`: Pago de abonos al INFONACOT
- `012`: Anticipo de salarios
- `013`: Pagos hechos con exceso al trabajador
- `014`: Errores
- `015`: Pérdidas
- `016`: Averías
- `017`: Adquisición de artículos producidos por la empresa o establecimiento
- `018`: Cuotas para la constitución y fomento de sociedades cooperativas y de cajas de ahorro
- `019`: Cuotas para el Fondo Nacional de la Vivienda para los Trabajadores
- `020`: Ausencia (Ausentismo)
- `021`: Cuotas sindicales
- `022`: Aplicación de saldos a favor por compensación anual
- `023`: Excedente de los límites establecidos en el artículo 84 de la LSS
- `024`: Aportaciones voluntarias al SAR
- `025`: Ajuste al neto pagado que no reúne los requisitos fiscales para ser deducible
- `026`: Ajuste por comprobación de la deducción
- `027`: Ajuste por devolución de la deducción
- `028`: Ajuste por aplicación de la deducción
- `029`: Ajuste por aplicación de la deducción
- `030`: Ajuste por aplicación de la deducción
- `031`: Ajuste por aplicación de la deducción
- `032`: Ajuste por aplicación de la deducción
- `033`: Ajuste por aplicación de la deducción
- `034`: Ajuste por aplicación de la deducción
- `035`: Ajuste por aplicación de la deducción
- `036`: Ajuste por aplicación de la deducción
- `037`: Ajuste por aplicación de la deducción
- `038`: Ajuste por aplicación de la deducción
- `039`: Ajuste por aplicación de la deducción
- `040`: Ajuste por aplicación de la deducción
- `041`: Ajuste por aplicación de la deducción
- `042`: Ajuste por aplicación de la deducción
- `043`: Ajuste por aplicación de la deducción
- `044`: Ajuste por aplicación de la deducción
- `045`: Ajuste por aplicación de la deducción
- `046`: Ajuste por aplicación de la deducción
- `047`: Ajuste por aplicación de la deducción
- `048`: Ajuste por aplicación de la deducción
- `049`: Ajuste por aplicación de la deducción
- `050`: Ajuste por aplicación de la deducción
- `051`: Ajuste por aplicación de la deducción
- `052`: Ajuste por aplicación de la deducción
- `053`: Ajuste por aplicación de la deducción
- `054`: Ajuste por aplicación de la deducción
- `055`: Ajuste por aplicación de la deducción
- `056`: Ajuste por aplicación de la deducción
- `057`: Ajuste por aplicación de la deducción
- `058`: Ajuste por aplicación de la deducción
- `059`: Ajuste por aplicación de la deducción
- `060`: Ajuste por aplicación de la deducción
- `061`: Ajuste por aplicación de la deducción
- `062`: Ajuste por aplicación de la deducción
- `063`: Ajuste por aplicación de la deducción
- `064`: Ajuste por aplicación de la deducción
- `065`: Ajuste por aplicación de la deducción
- `066`: Ajuste por aplicación de la deducción
- `067`: Ajuste por aplicación de la deducción

## Errores Comunes

### Error de Credenciales
```
Error: Error de autenticación: Credenciales inválidas
```
**Solución**: Verificar que las credenciales de Finkok sean correctas.

### RFC Inactivo
```
Error: El RFC emisor se encuentra inactivo en la cuenta
```
**Solución**: Activar el RFC en la cuenta de Finkok.

### Elementos Faltantes
```
Error: Se requieren tanto el usuario como la contraseña de Finkok
```
**Solución**: Proporcionar ambas credenciales.

## Notas Importantes

1. **Tipo de Comprobante**: Debe ser "N" para nómina
2. **Uso CFDI**: Debe ser "CN01" para nómina
3. **Clave de Producto/Servicio**: Usar "84111506" para servicios de nómina
4. **Objeto del Impuesto**: Usar "01" (No objeto del impuesto) para nómina
5. **Moneda**: Debe ser "MXN" para nómina
6. **Forma de Pago**: Usar "03" (Transferencia electrónica) para nómina

## Archivos Generados

- `NOMINA_EJEMPLO.xml`: XML completo del CFDI con complemento de nómina
- El archivo se guarda en la ruta especificada con el nombre proporcionado

## Dependencias

- PHP 7.4 o superior
- Extensión SOAP habilitada
- Extensión DOM habilitada
- Credenciales válidas de Finkok 