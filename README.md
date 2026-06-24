# 👕 Sistema de Ventas de Ropas y Calzados

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white)
![Composer](https://img.shields.io/badge/Composer-deps-885630?logo=composer&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-ready-2496ED?logo=docker&logoColor=white)
![Licencia](https://img.shields.io/badge/Licencia-MIT-green)

Sistema de punto de venta e inventario para una tienda de ropa y calzado: ventas, compras,
control de stock con variantes (tallas/colores), proveedores, caja y reportes. Construido en
**PHP** (patrón MVC) con **Composer** y base de datos **MySQL**.

---

## 🚀 Inicio rápido con Docker (recomendado)

La forma más fácil de levantar el proyecto es con Docker. No necesitas instalar PHP, Composer
ni MySQL en tu máquina: **solo Docker**.

### 1. Requisitos

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (Windows / macOS / Linux)

### 2. Clonar y arrancar

```bash
git clone https://github.com/brylop/tienda-moda.git
cd tienda-moda

# (Opcional) Personaliza credenciales copiando .env.example
cp .env.example .env

# Construir y levantar los contenedores
docker compose up -d --build
```

La primera vez tardará unos minutos mientras descarga las imágenes e instala dependencias.
Las siguientes veces es prácticamente instantáneo.

### 3. Acceder

| Servicio     | URL                                                  | Credenciales                |
|--------------|------------------------------------------------------|-----------------------------|
| **App web**  | http://localhost:8080                                | `admin@tienda.com` / `admin123` |
| **MySQL**    | `localhost:3307` (solo clientes externos)            | `tienda` / `tienda_pass`    |

> Si el puerto `8080` está ocupado, edita `APP_PORT` en `.env` y vuelve a levantar.

> ⚠️ `admin123` es una contraseña de **desarrollo**. Cámbiala desde el módulo **Perfil**
> antes de cualquier uso real. Más detalles en [🔑 Primer acceso (usuario inicial)](#-primer-acceso-usuario-inicial).

### 4. ¿Qué hace automáticamente?

1. Levanta MySQL 8.0 y espera a que esté sano.
2. Importa `bk_basededatos.sql` la **primera vez** (crea las 14 tablas y carga datos de ejemplo).
3. Levanta Apache + PHP 8.2 con el código del proyecto.
4. Si la base ya tiene datos, **no** los sobreescribe.

### 5. Comandos útiles

```bash
# Ver logs en vivo
docker compose logs -f app

# Entrar al contenedor de la app
docker compose exec app bash

# Reiniciar todo
docker compose restart

# Detener y eliminar contenedores (conserva los datos)
docker compose down

# Detener y ELIMINAR también los volúmenes (BORRAR DATOS)
docker compose down -v

# Reconstruir imagen tras cambios en el Dockerfile
docker compose build --no-cache
```

---

## 🛠️ Tecnologías

- **Backend:** PHP 8.2 (patrón MVC) + Composer
- **Base de datos:** MySQL 8.0
- **Frontend:** HTML, CSS, JavaScript + Bootstrap 5
- **Contenedores:** Docker + Docker Compose
- **Servidor web:** Apache 2.4 con mod_rewrite

---

## ✨ Características

- **Ventas** — registro de ventas con su detalle y anulación.
- **Compras** — registro de compras a proveedores con su detalle.
- **Productos con variantes** — catálogo con tallas/colores y categorías.
- **Inventario (Kardex)** — control de stock y movimientos.
- **Etiquetas** — generación de PDFs con códigos de barras (FPDF + picqer).
- **Clientes y proveedores** — administración de la cartera comercial.
- **Caja por sesiones** — apertura/cierre de caja y control de movimientos.
- **Gastos** — registro de gastos.
- **Reportes** — reportes del negocio y exportación a Excel.
- **Usuarios, perfil y configuración** — autenticación con `password_hash`, perfil y datos de la empresa.

---

## 📋 Instalación manual (sin Docker)

Si prefieres ejecutar el proyecto sin Docker:

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
   ```bash
   mysql -u root -p
   CREATE DATABASE sistema_moda CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   EXIT;
   mysql -u root -p sistema_moda < bk_basededatos.sql
   ```

4. **Configurar credenciales** — Crea un archivo `.env` en la raíz:
   ```
   DB_HOST=localhost
   DB_PORT=3306
   DB_NAME=sistema_moda
   DB_USER=root
   DB_PASS=
   ```

5. **Servir la aplicación** — El punto de entrada es `public/`. Puedes usar:
   ```bash
   php -S localhost:8000 -t public
   ```
   O apuntar Apache/Nginx al directorio `public/`.

6. **Establecer la contraseña del admin** (primer acceso) — la base sembrada no trae la
   contraseña en texto plano. Desde la raíz del proyecto:
   ```bash
   php -r '$pdo=new PDO("mysql:host=localhost;dbname=sistema_moda;charset=utf8mb4","root","");$h=password_hash("CambiaEstaClave#2026",PASSWORD_DEFAULT);$s=$pdo->prepare("UPDATE usuarios SET password=:p WHERE email=:e");$s->execute([":p"=>$h,":e"=>"admin@tienda.com"]);echo "OK\n";'
   ```
   Ajusta usuario/contraseña de MySQL según tu `.env`. Luego entra con `admin@tienda.com`.

---

## 🔑 Primer acceso (usuario inicial)

Al levantar el proyecto desde cero, `bk_basededatos.sql` siembra automáticamente dos usuarios
**con contraseña ya definida** (`admin123`):

| Usuario            | Contraseña | Rol        | Para qué sirve                                                        |
|--------------------|------------|------------|----------------------------------------------------------------------|
| `admin@tienda.com` | `admin123` | **admin**  | **Empieza con este.** Acceso total: configuración, usuarios, productos, reportes, caja. |
| `mario@tienda.com` | `admin123` | vendedor   | Acceso limitado a operaciones de venta.                              |

Para configurar el sistema, inicia sesión en http://localhost:8080 con **`admin@tienda.com`**
/ `admin123`. No se requiere ningún paso manual: la contraseña ya viene en los datos sembrados.

> 🔒 En la base de datos solo se almacena el *hash* (bcrypt), nunca la contraseña en texto
> plano. La ruta de auto-creación de admin (`/auth/setup`) fue **eliminada** por seguridad.

> ⚠️ `admin123` es una contraseña pensada para **desarrollo local**. Cámbiala cuanto antes
> desde el módulo **Perfil**, y gestiona los demás usuarios desde el módulo **Usuarios**.

### Cambiar la contraseña sembrada (opcional)

Si quieres que el proyecto arranque con **otra** contraseña por defecto, regenera el *hash*
y reemplázalo en `bk_basededatos.sql` (líneas de `INSERT INTO usuarios`):

```bash
# Genera el hash de tu nueva contraseña
docker exec tienda-moda-app php -r 'echo password_hash("TuNuevaClave", PASSWORD_DEFAULT)."\n";'
```

Pega el valor resultante en el campo `password` del usuario correspondiente dentro de
`bk_basededatos.sql` y vuelve a levantar con un volumen limpio (`docker compose down -v && docker compose up -d`).

---

## 🛡️ Notas de seguridad

Este proyecto incluye las siguientes protecciones; tenlas en cuenta al desplegar:

- **Sin ruta de setup pública.** Se eliminó `/auth/setup` (que creaba un admin con
  contraseña fija accesible por cualquiera).
- **Protección CSRF** en todas las peticiones `POST` (formularios y `fetch` JSON). El token
  se valida de forma centralizada en `public/index.php`; las vistas lo inyectan con
  `Csrf::field()` / `Csrf::meta()` (helper en `app/core/Csrf.php`).
- **Contraseñas con `password_hash`** (bcrypt) y **consultas preparadas** (PDO) en todo el código.

Antes de exponer el sistema a Internet:

1. Cambia las contraseñas de **ambos** usuarios sembrados (`admin` y `mario`); sus *hashes*
   están en el repositorio público.
2. Cambia las credenciales de base de datos en `.env` (no uses `tienda_pass` / `root_pass`).
3. Asegúrate de que `.env` **no** se suba al repositorio (ya está en `.gitignore`).
4. En producción, considera quitar el *bind-mount* `./:/var/www/html` de `docker-compose.yml`
   para servir el código «horneado» en la imagen en lugar del código del host.

---

## 📂 Estructura del proyecto

```
tienda-moda/
├── app/
│   ├── controllers/      # Controladores MVC (Auth, Ventas, Productos, etc.)
│   ├── core/             # Núcleo (Database.php, Csrf.php)
│   ├── models/           # Modelos (Usuario, Venta, Producto, Caja, …)
│   └── views/            # Vistas agrupadas por módulo
├── config/               # Configuración (opcional, actualmente se usa .env)
├── public/               # Punto de entrada público (DocumentRoot)
│   ├── index.php         # Front controller + router
│   └── .htaccess         # Rewrite rules
├── docker/
│   ├── entrypoint.sh     # Espera a MySQL e importa SQL
│   └── php.ini           # Configuración PHP
├── docker-compose.yml    # Orquestación app + db
├── Dockerfile            # Imagen PHP 8.2 + Apache
├── bk_basededatos.sql    # Esquema y datos de ejemplo
├── composer.json         # Dependencias (FPDF + barcode-generator)
├── .env.example          # Plantilla de variables de entorno
└── README.md
```

---

## ⚙️ Variables de entorno

Todas las variables son opcionales (hay valores por defecto razonables):

| Variable          | Default            | Descripción                                        |
|-------------------|--------------------|----------------------------------------------------|
| `APP_PORT`        | `8080`             | Puerto donde se expone la app                      |
| `APP_BASE_PATH`   | *(vacío)*          | Subcarpeta de la app (`/tienda-moda`) o raíz       |
| `APP_ENV`         | `production`       | `production` / `development`                       |
| `DB_HOST`         | `db`               | Host de MySQL (interno al compose)                 |
| `DB_PORT`         | `3306`             | Puerto interno de MySQL                            |
| `DB_NAME`         | `sistema_moda`     | Nombre de la base de datos                         |
| `DB_USER`         | `tienda`           | Usuario de la app                                  |
| `DB_PASS`         | `tienda_pass`      | Contraseña del usuario de la app                   |
| `DB_ROOT_PASS`    | `root_pass`        | Contraseña del usuario root                        |
| `DB_EXTERNAL_PORT`| `3307`             | Puerto externo para clientes MySQL                 |

---

## 👤 Autor

**Brayan Lopez** — [github.com/brylop](https://github.com/brylop)

## 📄 Licencia

Distribuido bajo la licencia **MIT**. Consulta el archivo [LICENSE](LICENSE) para más detalles.