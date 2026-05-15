# 🔄 Aplicación Web de Gestión de Reciclaje - IE Cincuentenario de Fabricato

Plataforma CRUD completa para registrar, controlar y analizar el ingreso de residuos reciclables con indicadores automáticos y reportes detallados.

## ✨ Características Principales

✅ **Registro CRUD de Residuos**
- Crear, leer, actualizar y eliminar registros de residuos
- Formulario intuitivo con validación automática
- Clasificación automática según tipo y estado

✅ **Clasificación Automática**
- Papel limpio → Reciclable
- Papel sucio → No aprovechable
- PET limpio → Reciclable
- PET sucio → No aprovechable
- Orgánico → Compostaje

✅ **Dashboard de Control**
- Visualización en tiempo real de KPIs
- 6 indicadores clave principales
- Gráficos interactivos con Chart.js

✅ **Reportes Avanzados**
- Filtrado por tipo de residuo, grado, fecha
- Cálculo automático de indicadores
- Exportación de datos tabulares

✅ **Indicadores Calculados Automáticamente**
- **Total de residuos generados** (kg)
- **Residuos aprovechables** vs **No aprovechables**
- **Tasa de aprovechamiento** (%)
- **Generación per cápita** (kg/estudiante)
- **Porcentaje por tipo de residuo** (%)
- **Residuos por salón, grado y fecha**

✅ **Visualizaciones**
- Gráfico de pastel: Residuos por tipo
- Gráfico de barras: Residuos por salón y grado
- Gráfico circular: Aprovechamiento (Aprovechable/No aprovechable)
- Hallazgos principales destacados

## 🛠️ Tecnologías Utilizadas

- **Backend:** PHP 7.4+
- **Base de Datos:** MySQL 5.7+
- **Frontend:** HTML5, CSS3, JavaScript ES6+
- **Gráficos:** Chart.js
- **Responsive Design:** CSS Grid y Flexbox

## 📋 Requisitos del Sistema

- Servidor web con PHP 7.4 o superior (Apache, Nginx)
- MySQL 5.7 o superior
- Navegador web moderno (Chrome, Firefox, Safari, Edge)
- Mínimo 100MB de espacio en disco

## 🚀 Instalación Paso a Paso

### 1. Descargar/Clonar el Repositorio

```bash
git clone https://github.com/hecortaz/reciclaje_IECF.git
cd reciclaje_IECF
```

### 2. Crear Base de Datos

Abrir **phpMyAdmin** o **MySQL Client** y ejecutar:

```bash
mysql -u root -p < schema.sql
```

O importar el archivo `schema.sql` directamente desde phpMyAdmin.

### 3. Configurar Conexión a Base de Datos

Editar el archivo `config/database.php`:

```php
$db_host = 'localhost';     // Host de MySQL
$db_user = 'root';          // Usuario MySQL
$db_password = '';          // Contraseña MySQL
$db_name = 'waste_management'; // Nombre de la BD
```

### 4. Colocar en Servidor Web

Copiar todos los archivos a:
- **Apache:** `C:\xampp\htdocs\reciclaje_IECF` (Windows)
- **Apache:** `/var/www/html/reciclaje_IECF` (Linux)
- **Nginx:** Según tu configuración

### 5. Acceder a la Aplicación

Abrir en navegador:
```
http://localhost/reciclaje_IECF/
```

## 📁 Estructura de Carpetas

```
reciclaje_IECF/
├── config/
│   └── database.php          # Configuración de conexión
├── includes/
│   ├── header.php            # Header navegación
│   └── footer.php            # Footer
├── css/
│   └── styles.css            # Estilos principales
├── js/
│   ├── main.js               # Funciones JavaScript
│   └── charts.js             # Gráficos interactivos
├── api/
│   ├── get_waste.php         # Obtener registro
│   ├── register_waste.php    # Crear registro
│   ├── update_waste.php      # Actualizar registro
│   └── delete_waste.php      # Eliminar registro
├── index.php                 # Página principal
├── dashboard.php             # Dashboard analítico
├── reports.php               # Reportes y filtros
├── schema.sql                # Script base de datos
└── README.md                 # Este archivo
```

## 📊 Base de Datos

### Tabla: `residuos`

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | INT | Identificador único |
| fecha | DATE | Fecha del registro |
| grado | VARCHAR(10) | Grado escolar (6°-11°) |
| salon | VARCHAR(50) | Nombre del salón |
| tipo_residuo | ENUM | Papel, PET, Orgánico |
| peso_kg | DECIMAL(10,2) | Peso en kilogramos |
| cantidad | INT | Cantidad de unidades |
| numero_estudiantes | INT | Estudiantes que participaron |
| estado_residuo | ENUM | Aprovechable/No aprovechable |
| clasificacion | VARCHAR(100) | Clasificación automática |
| created_at | TIMESTAMP | Fecha de creación |
| updated_at | TIMESTAMP | Fecha de actualización |

## 🎯 Variables y Cálculos Automáticos

### Variables Primarias Capturadas
```
• Fecha de registro
• Grado (6° a 11°)
• Salón
• Tipo de residuo (Papel, PET, Orgánico)
• Peso (kg)
• Cantidad (unidades)
• Número de estudiantes
• Estado (Aprovechable/No aprovechable)
```

### Variables Derivadas Calculadas Automáticamente

#### 1. **Tasa de Aprovechamiento (TA)**
```
TA = (Residuos Aprovechables / Total de Residuos) × 100
```

#### 2. **Generación Per Cápita (G)**
```
G = Total de kg de residuos / Número total de estudiantes
```

#### 3. **Porcentaje por Tipo de Residuo**
```
% = (Peso del tipo de residuo / Peso total) × 100
```

#### 4. **Residuos por Salón**
```
Sum(peso_kg) GROUP BY salon
```

#### 5. **Residuos por Grado**
```
Sum(peso_kg) GROUP BY grado
```

## 🖥️ Pantallas y Funcionalidades

### 1. **Página de Inicio (index.php)**
- Formulario CRUD completo
- Tabla con últimos 20 registros
- Botones Editar/Eliminar
- Clasificación automática en tiempo real

### 2. **Dashboard (dashboard.php)**
- 6 tarjetas de indicadores clave
- 4 gráficos interactivos:
  - Residuos por tipo (gráfico pastel)
  - Aprovechamiento (gráfico circular)
  - Top salones (gráfico barras)
  - Residuos por grado (gráfico barras)
- Hallazgos principales destacados

### 3. **Reportes (reports.php)**
- Filtros avanzados:
  - Por tipo de residuo
  - Por grado
  - Por rango de fechas
- Tabla detallada de registros
- Gráfico de porcentajes por tipo
- Estadísticas filtradas en tiempo real

## 📈 Indicadores que Muestra la App

✓ Total de residuos generados (kg)
✓ Residuos aprovechables (kg)
✓ Residuos no aprovechables (kg)
✓ Tasa de aprovechamiento (%)
✓ Generación per cápita (kg/estudiante)
✓ Residuos por salón
✓ Residuos por grado
✓ Residuos por tipo
✓ Comparación entre salones
✓ Hallazgos principales automáticos

## 💡 Ejemplos de Hallazgos Automáticos

La aplicación genera automáticamente análisis como:

- "El grado 8° genera más residuos (15.3 kg)"
- "El 65% de los residuos son aprovechables"
- "El restaurante genera mayor cantidad de residuos orgánicos (5.0 kg)"
- "La tasa de aprovechamiento general es 72.5%"
- "Generación promedio por estudiante: 0.45 kg"

## 🔐 Seguridad

✅ **Protección contra Inyección SQL**
```php
// Uso de funciones sanitize()
$variable = sanitize($_POST['valor'], $conn);
```

✅ **Validación de Formularios**
- Validación en cliente (JavaScript)
- Validación en servidor (PHP)
- Campos requeridos

✅ **Manejo de Errores**
- Try-catch para conexiones
- Mensajes de error seguros
- Log de operaciones

## 🎨 Diseño y UX

- **Interfaz moderna y colorida** con gradientes
- **Responsive design** - Funciona en móvil, tablet y escritorio
- **Animaciones suaves** en botones y tarjetas
- **Colores intuitivos:**
  - Verde para "Aprovechable"
  - Rojo para "No aprovechable"
  - Azul para información general
  - Naranja para porcentajes

## 📱 Responsividad

La aplicación es completamente responsive:
- ✓ Escritorio (1200px+)
- ✓ Tablets (768px - 1199px)
- ✓ Móviles (menos de 768px)

## 🚦 Flujo de Usuario

```
1. Usuario accede a index.php
2. Completa formulario de registro
3. Sistema clasifica automáticamente
4. Se guarda en base de datos
5. Usuario visualiza en tabla
6. Puede editar o eliminar
7. Va a Dashboard para ver análisis
8. Filtra en Reportes para análisis detallados
```

## 📊 Ejemplos de SQL Internos

### Obtener total de residuos
```sql
SELECT SUM(peso_kg) as total FROM residuos;
```

### Residuos aprovechables
```sql
SELECT SUM(peso_kg) FROM residuos WHERE estado_residuo='Aprovechable';
```

### Residuos por salón
```sql
SELECT salon, SUM(peso_kg) as total FROM residuos GROUP BY salon;
```

### Residuos por grado
```sql
SELECT grado, SUM(peso_kg) as total FROM residuos GROUP BY grado;
```

## 🐛 Solución de Problemas

### Error: "Error de conexión"
- Verificar credenciales en `config/database.php`
- Asegurar que MySQL está corriendo
- Verificar nombre de la base de datos

### Error: "Tabla no encontrada"
- Ejecutar `schema.sql` nuevamente
- Verificar nombre correcto: `waste_management`

### Los gráficos no aparecen
- Verificar conexión a internet (Chart.js CDN)
- Abrir consola del navegador (F12) para errores
- Limpiar caché del navegador

### Formulario no guarda datos
- Verificar permisos de carpetas
- Asegurar que PHP puede escribir en BD
- Revisar logs de error de PHP

## 📞 Soporte

Para reportar problemas o sugerencias:
1. Revisar los logs en el servidor
2. Verificar la consola del navegador (F12)
3. Contactar al desarrollador

## 📄 Licencia

Proyecto desarrollado para IE Cincuentenario de Fabricato

## ✍️ Autor

**Héctor Cortázar**
- GitHub: [@hecortaz](https://github.com/hecortaz)
- Aplicación de Gestión de Reciclaje - 2026

## 🎓 Propósito Educativo

Esta aplicación fue desarrollada para:
- Fomentar la cultura del reciclaje en la institución
- Proporcionar datos reales para análisis ambiental
- Demostrar el impacto de la gestión de residuos
- Servir como proyecto de ciencia y tecnología en la feria

---

**¡Contribuyendo a un mundo más verde! 🌱**
