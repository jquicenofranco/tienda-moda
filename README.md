# 👕 Sistema de Ventas de Ropas y Calzados

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white)
![Composer](https://img.shields.io/badge/Composer-deps-885630?logo=composer&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?logo=mysql&logoColor=white)
![Licencia](https://img.shields.io/badge/Licencia-MIT-green)

Sistema de punto de venta e inventario para una tienda de ropa y calzado: ventas, compras,
control de stock con variantes (tallas/colores), proveedores, caja y reportes. Construido en
**PHP** (patrón MVC) con **Composer** y base de datos **MySQL**.

## ✨ Características

- **Ventas** — registro de ventas con su detalle.
- **Compras** — registro de compras a proveedores con su detalle.
- **Productos con variantes** — catálogo de productos con variantes (tallas/colores) y categorías.
- **Inventario (Kardex)** — control de stock y movimientos.
- **Etiquetas** — generación/impresión de etiquetas de producto.
- **Clientes y proveedores** — administración de la cartera comercial.
- **Caja por sesiones** — apertura/cierre de caja y control de movimientos.
- **Gastos** — registro de gastos.
- **Reportes** — reportes del negocio.
- **Usuarios, perfil y configuración** — autenticación, perfil y datos de la empresa.

## 🛠️ Tecnologías

- **Backend:** PHP (patrón MVC) + Composer
- **Base de datos:** MySQL / MariaDB
- **Frontend:** HTML, CSS, JavaScript
- **Entorno de desarrollo:** Laragon / Apache

## 📋 Requisitos

- PHP 8.0 o superior
- Composer
- MySQL 8.x o MariaDB

## 🚀 Instalación

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/brylop/tienda-moda.git
   cd tienda-moda
   ```

2. **Instalar dependencias**
   ```bash
   composer install
   ```

3. **Crear e importar la base de datos**
   - Crea la base de datos e importa [`bk_basededatos.sql`](bk_basededatos.sql).

4. **Configurar la conexión**
   - Ajusta la configuración en `config/` con tus credenciales de MySQL.

5. **Levantar el proyecto**
   - Coloca la carpeta en el directorio web (el punto de entrada está en `public/`).

## 🔑 Credenciales de demostración

| Usuario            | Contraseña   |
|--------------------|--------------|
| `admin@tienda.com` | `Xvito2013$` |

> Credenciales correspondientes a los datos de ejemplo. Cámbialas antes de cualquier uso real.

## 📂 Estructura del proyecto

```
tienda_moda/
├── app/               # Controladores, modelos y vistas (MVC)
├── config/            # Configuración de conexión
├── public/            # Punto de entrada público
└── bk_basededatos.sql # Esquema y datos de ejemplo
```

## 📸 Capturas

_Próximamente._

## 👤 Autor

**Brayan Lopez** — [github.com/brylop](https://github.com/brylop)

## 📄 Licencia

Distribuido bajo la licencia **MIT**. Consulta el archivo [LICENSE](LICENSE) para más detalles.
