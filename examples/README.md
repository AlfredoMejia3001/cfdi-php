# 📚 Ejemplos de la Librería CFDI PHP

Esta carpeta contiene ejemplos completos y funcionales de cada complemento implementado en la librería.

## 🚀 Ejemplos Disponibles

### 1. **ejemplo_cfdi_basico.php** - CFDI 4.0 Básico
**Descripción**: Ejemplo de CFDI 4.0 sin complementos, con impuestos básicos.
**Características**:
- ✅ Creación de CFDI 4.0 estándar
- ✅ Configuración de comprobante, emisor y receptor
- ✅ Agregado de conceptos
- ✅ Configuración de impuestos (IVA)
- ✅ Procesamiento completo con Finkok

**Uso**:
```bash
php ejemplo_cfdi_basico.php
```

### 2. **ejemplo_pagos.php** - Complemento de Pagos 2.0
**Descripción**: Ejemplo completo del complemento de Pagos 2.0 con documentos relacionados e impuestos.
**Características**:
- ✅ CFDI con complemento de Pagos 2.0
- ✅ Configuración de pagos individuales
- ✅ Documentos relacionados con impuestos
- ✅ Manejo de impuestos retenidos y trasladados
- ✅ Procesamiento completo con Finkok

**Uso**:
```bash
php ejemplo_pagos.php
```

### 3. **ejemplo_cartaporte.php** - Complemento de Carta Porte 3.1
**Descripción**: Ejemplo completo del complemento de Carta Porte 3.1 para transporte de mercancías.
**Características**:
- ✅ CFDI con complemento de Carta Porte 3.1
- ✅ Configuración de ubicaciones (origen y destino)
- ✅ Configuración de mercancías y totales
- ✅ Configuración de operador de transporte
- ✅ Procesamiento completo con Finkok

**Uso**:
```bash
php ejemplo_cartaporte.php
```

### 4. **ejemplo_nomina.php** - Complemento de Nómina 1.2
**Descripción**: Ejemplo completo del complemento de Nómina 1.2 para pagos de nómina.
**Características**:
- ✅ CFDI con complemento de Nómina 1.2
- ✅ Configuración de fechas de pago y tipo de nómina
- ✅ Configuración de emisor y receptor de nómina
- ✅ Configuración de percepciones y deducciones
- ✅ Procesamiento completo con Finkok

**Uso**:
```bash
php ejemplo_nomina.php
```

## 🔧 Requisitos Previos

Antes de ejecutar los ejemplos, asegúrate de tener:

1. **PHP 7.4 o superior** instalado
2. **Composer** instalado
3. **Extensiones PHP** requeridas:
   - `ext-soap`
   - `ext-dom`
   - `ext-libxml`
4. **Dependencias** instaladas:
   ```bash
   composer install
   ```

## 📋 Configuración

### Credenciales de Finkok
Los ejemplos usan credenciales de prueba. Para uso en producción, modifica:

```php
$finkokUser = 'usuario_finkok_demo';
$finkokPass = 'pass_finkok_demo';
```

### Certificados
Los ejemplos usan un certificado de prueba. Para uso en producción, modifica:

```php
->comprobante(
    noCertificado: 'TU_NUMERO_CERTIFICADO_REAL',
    // ... otros parámetros
)
```

## 📁 Archivos de Salida

Todos los ejemplos generan archivos XML en la carpeta `output/`:
- `CFDI_BASICO.xml`
- `CFDI_PAGOS.xml`
- `CFDI_CARTA_PORTE.xml`
- `CFDI_NOMINA.xml`

## 🎯 Casos de Uso

### CFDI Básico
- Facturas de servicios
- Facturas de productos
- Notas de crédito/debito básicas

### Complemento de Pagos
- Recibos de pago
- Aplicación de pagos a facturas
- Manejo de impuestos en pagos

### Complemento de Carta Porte
- Transporte de mercancías
- Logística y distribución
- Operaciones de transporte

### Complemento de Nómina
- Pagos de nómina
- Recibos de nómina
- Manejo de percepciones y deducciones

## 🔍 Solución de Problemas

### Error de credenciales
- Verifica que las credenciales de Finkok sean correctas
- Asegúrate de que la cuenta tenga saldo

### Error de certificado
- Verifica que el número de certificado sea válido
- Asegúrate de que el certificado no haya expirado

### Error de validación
- Revisa que todos los campos requeridos estén completos
- Verifica que los valores sean del formato correcto

## 📞 Soporte

Para dudas o problemas con los ejemplos:
1. Revisa la documentación principal de la librería
2. Verifica que todas las dependencias estén instaladas
3. Revisa los logs de error para más detalles

---

**¡Disfruta usando la librería CFDI PHP! 🎉**
