# CFDI PHP SDK - Índice de Documentación

## 📚 Documentación Principal

### [README.md](README.md) - Documentación Completa
Documentación integral del SDK con ejemplos de uso para todas las clases.

## 📖 Documentación Específica por Clase

### [README_CartaPorte31.md](README_CartaPorte31.md) - Carta Porte 3.1
Documentación detallada para el complemento de Carta Porte, incluyendo:
- Configuración de ubicaciones
- Manejo de mercancías
- Figuras de transporte
- Ejemplos completos

### [README_Nomina12.md](README_Nomina12.md) - Nómina 1.2
Documentación completa para el complemento de Nómina, incluyendo:
- Percepciones y deducciones
- Otros pagos
- Incapacidades
- Catálogos del SAT

## 🏗️ Estructura del Proyecto

```
cfdi-php/
├── src/                          # Código fuente
│   ├── CFDI40.php               # CFDI básico 4.0
│   ├── RecepcionPagos.php       # Recepción de Pagos
│   ├── CartaPorte31.php         # Carta Porte 3.1
│   └── Nomina12.php             # Nómina 1.2
├── README.md                     # Documentación principal
├── README_CartaPorte31.md       # Documentación Carta Porte
├── README_Nomina12.md           # Documentación Nómina
├── INDEX.md                      # Este archivo
└── composer.json                 # Dependencias
```

## 🚀 Inicio Rápido

### 1. Instalación
```bash
git clone https://github.com/AlfredoMejia3001/cfdi-php.git
cd cfdi-php-sdk/cfdi-php
composer install
```

### 2. Configuración
```php
// Configurar credenciales de Finkok
$finkokUser = "tu_usuario@finkok.com.mx";
$finkokPass = "tu_password";
```

### 3. Uso Básico
```php
require_once 'src/CFDI40.php';
$cfdi = new CFDI40();
// Ver README.md para ejemplos completos
```

## 📋 Clases Disponibles

| Clase | Propósito | Tipo CFDI | Complemento |
|-------|-----------|-----------|-------------|
| **CFDI40.php** | Facturación básica | I, E, T, N, P | Sin complemento |
| **RecepcionPagos.php** | Abonos y pagos | P | Recepción de Pagos 2.0 |
| **CartaPorte31.php** | Transporte de mercancías | T | Carta Porte 3.1 |
| **Nomina12.php** | Nóminas y recursos humanos | N | Nómina 1.2 |

## 🎯 Casos de Uso

### Para Facturas Simples
- **Clase**: `CFDI40.php`
- **Documentación**: [README.md](README.md#1-cfdi40php---facturación-básica)
- **Tipo de Comprobante**: `I` (Ingreso)

### Para Abonos y Pagos
- **Clase**: `RecepcionPagos.php`
- **Documentación**: [README.md](README.md#2-recepcionpagosphp---recepción-de-pagos)
- **Tipo de Comprobante**: `P` (Pago)

### Para Transporte de Mercancías
- **Clase**: `CartaPorte31.php`
- **Documentación**: [README_CartaPorte31.md](README_CartaPorte31.md)
- **Tipo de Comprobante**: `T` (Traslado)

### Para Nóminas
- **Clase**: `Nomina12.php`
- **Documentación**: [README_Nomina12.md](README_Nomina12.md)
- **Tipo de Comprobante**: `N` (Nómina)

## 🔍 Búsqueda de Información

### Por Funcionalidad
- **Impuestos**: Ver [README.md](README.md#impuestos)
- **Validaciones**: Ver [README.md](README.md#validaciones-y-errores)
- **Errores**: Ver [README.md](README.md#errores-comunes)
- **Configuración**: Ver [README.md](README.md#configuración-avanzada)

### Por Clase
- **CFDI40**: [README.md](README.md#1-cfdi40php---facturación-básica)
- **Recepción de Pagos**: [README.md](README.md#2-recepcionpagosphp---recepción-de-pagos)
- **Carta Porte**: [README_CartaPorte31.md](README_CartaPorte31.md)
- **Nómina**: [README_Nomina12.md](README_Nomina12.md)

## 🛠️ Herramientas y Recursos

### Validación
- **Finkok**: Servicios de validación automática
- **Esquemas XSD**: Validación contra esquemas del SAT
- **Logging**: Sistema de registro de errores

### Desarrollo
- **Ejemplos**: Código de ejemplo para cada clase
- **Troubleshooting**: Solución de problemas comunes
- **Configuración**: Opciones avanzadas de configuración

## 📞 Soporte

### Documentación
- **Principal**: [README.md](README.md)
- **Específica**: Archivos README por clase
- **Índice**: Este archivo

### Recursos Externos
- **SAT**: [www.sat.gob.mx](https://www.sat.gob.mx)
- **Finkok**: [www.finkok.com](https://www.finkok.com)
- **GitHub**: Repositorio del proyecto

## 🔄 Actualizaciones

### Versión Actual
- **SDK**: v1.4.0
- **Última Actualización**: Enero 2025
- **PHP Mínimo**: 7.4

### Historial de Cambios
- **v1.0.0**: CFDI 4.0 básico
- **v1.1.0**: Recepción de Pagos
- **v1.2.0**: Carta Porte 3.1
- **v1.3.0**: Nómina 1.2
- **v1.4.0**: Documentación completa y mejoras

## 📝 Notas Importantes

1. **Credenciales**: Siempre usa credenciales válidas de Finkok
2. **Validación**: Todas las clases incluyen validaciones automáticas
3. **XML**: Los archivos se generan conforme a esquemas del SAT
4. **Errores**: Revisa la sección de troubleshooting para problemas comunes

---

**Para comenzar**: Lee [README.md](README.md) para una introducción completa al SDK.

**Para casos específicos**: Consulta la documentación especializada de cada clase.

**Para soporte**: Revisa la sección de troubleshooting o crea un issue en GitHub.
