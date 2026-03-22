# API ERP v1

Base profesional para un ERP comercial orientado a API REST construida con Laravel, PostgreSQL y Laravel Sanctum. El proyecto prioriza seguridad, mantenibilidad, escalabilidad y una arquitectura por capas con control de acceso, auditoría, facturación transaccional e inventario.

## Características principales

- Autenticación segura con Laravel Sanctum.
- API REST sin Blade ni vistas web.
- PostgreSQL como motor principal.
- Respuestas JSON consistentes en español.
- Roles y permisos con middleware de autorización.
- CRUD básico para usuarios, roles, permisos, clientes y productos.
- Facturación con validación de stock, transacción completa y movimientos de inventario.
- Auditoría básica para eventos importantes.

## Requisitos

- PHP 8.3 o superior
- Composer 2.9 o superior
- PostgreSQL 14 o superior
- Extensiones PHP `pdo_pgsql`, `pgsql`, `bcmath`, `mbstring`

## Instalación

```bash
composer install
copy .env.example .env
php artisan key:generate
```

## Configuración de PostgreSQL en `.env`

El proyecto está preparado para usar PostgreSQL. Ajusta estos valores según tu entorno:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=apierp
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

Configuración adicional recomendada:

```env
APP_NAME="API ERP"
APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_TIMEZONE=America/El_Salvador
SANCTUM_EXPIRACION_MINUTOS=480
ERP_PORCENTAJE_IMPUESTO=13
ERP_PREFIJO_FACTURA=FAC
ERP_PAGINACION=15
```

En producción usa `APP_DEBUG=false` y gestiona secretos únicamente desde variables de entorno.

## Sanctum

Laravel Sanctum ya está instalado y configurado como sistema principal de autenticación para tokens personales.

Puntos relevantes:

- Login: genera un token nuevo y revoca tokens previos del usuario.
- Logout: revoca el token actual.
- Rutas privadas: protegidas con `auth:sanctum`.
- Expiración del token: controlada por `SANCTUM_EXPIRACION_MINUTOS`.

## Migraciones

Ejecuta las migraciones con:

```bash
php artisan migrate
```

Tablas de negocio principales:

- `users`
- `roles`
- `permisos`
- `rol_usuario`
- `permiso_rol`
- `clientes`
- `productos`
- `facturas`
- `detalle_factura`
- `movimientos_inventario`
- `auditoria`
- `personal_access_tokens`

## Seeders

Carga los datos base con:

```bash
php artisan db:seed
```

O en una sola ejecución:

```bash
php artisan migrate --seed
```

Seeders incluidos:

- `PermisoSeeder`
- `RolSeeder`
- `UsuarioAdministradorSeeder`

## Credenciales iniciales

- Correo: `admin@apierp.local`
- Contraseña: `Admin123!`

## Iniciar el servidor

```bash
php artisan serve
```

La API quedará disponible en:

```text
http://localhost:8000/api
```

## Cómo hacer login

Endpoint:

```http
POST /api/login
Content-Type: application/json
Accept: application/json
```

Cuerpo:

```json
{
  "email": "admin@apierp.local",
  "password": "Admin123!"
}
```

## Cómo usar el token de Sanctum

Incluye el token devuelto por login en el header `Authorization`:

```http
Authorization: Bearer TU_TOKEN
Accept: application/json
```

Ejemplo para obtener el perfil autenticado:

```http
GET /api/me
Authorization: Bearer TU_TOKEN
Accept: application/json
```

## Endpoints principales

### Autenticación

- `POST /api/login`
- `POST /api/logout`
- `GET /api/me`

### Usuarios

- `GET /api/usuarios`
- `POST /api/usuarios`
- `GET /api/usuarios/{id}`
- `PUT /api/usuarios/{id}`

### Roles

- `GET /api/roles`
- `POST /api/roles`
- `GET /api/roles/{id}`
- `PUT /api/roles/{id}`

### Permisos

- `GET /api/permisos`
- `POST /api/permisos`
- `GET /api/permisos/{id}`
- `PUT /api/permisos/{id}`

### Clientes

- `GET /api/clientes`
- `POST /api/clientes`
- `GET /api/clientes/{id}`
- `PUT /api/clientes/{id}`

### Productos

- `GET /api/productos`
- `POST /api/productos`
- `GET /api/productos/{id}`
- `PUT /api/productos/{id}`

### Facturas

- `GET /api/facturas`
- `POST /api/facturas`
- `GET /api/facturas/{id}`

### Inventario

- `GET /api/movimientos-inventario`

## Formato estándar de respuestas

### Éxito

```json
{
  "exito": true,
  "mensaje": "Operación realizada correctamente",
  "datos": {}
}
```

### Error

```json
{
  "exito": false,
  "mensaje": "Ocurrió un error en la operación",
  "errores": {}
}
```

### Validación

```json
{
  "exito": false,
  "mensaje": "Errores de validación",
  "errores": {
    "campo": [
      "mensaje"
    ]
  }
}
```

## Ejemplo de request/response de login

### Request

```http
POST /api/login
Content-Type: application/json
Accept: application/json
```

```json
{
  "email": "admin@apierp.local",
  "password": "Admin123!"
}
```

### Response

```json
{
  "exito": true,
  "mensaje": "Inicio de sesión realizado correctamente.",
  "datos": {
    "token": "1|ejemplo_token_generado_por_sanctum",
    "tipo_token": "Bearer",
    "usuario": {
      "id": 1,
      "nombre": "Administrador General",
      "email": "admin@apierp.local",
      "estado": "activo"
    }
  }
}
```

## Ejemplo de request/response de crear factura

### Request

```http
POST /api/facturas
Authorization: Bearer TU_TOKEN
Content-Type: application/json
Accept: application/json
```

```json
{
  "cliente_id": 1,
  "fecha": "2026-03-22",
  "observaciones": "Venta mostrador",
  "detalle": [
    {
      "producto_id": 1,
      "cantidad": 2,
      "precio_unitario": 10.00
    },
    {
      "producto_id": 2,
      "cantidad": 1,
      "precio_unitario": 25.50
    }
  ]
}
```

### Response

```json
{
  "exito": true,
  "mensaje": "Factura creada correctamente.",
  "datos": {
    "id": 1,
    "numero": "FAC-00000001",
    "fecha": "2026-03-22",
    "subtotal": "45.50",
    "impuesto": "5.92",
    "total": "51.42",
    "estado": "emitida"
  }
}
```

## Reglas críticas de facturación

- El backend recalcula subtotal, impuesto y total.
- No se aceptan facturas sin líneas.
- No se permite vender a clientes inactivos.
- No se permite vender productos inactivos.
- No se permite stock negativo.
- Cada venta descuenta inventario y registra movimiento.
- Toda la operación se ejecuta dentro de una transacción.

## Auditoría básica incluida

Se registran al menos estos eventos:

- Login
- Logout
- Creación de cliente
- Actualización de cliente
- Creación de producto
- Actualización de producto
- Creación de factura

## Estructura relevante

```text
app/
  Enums/
  Http/
    Controllers/Api/
    Middleware/
    Requests/
    Resources/
  Models/
  Services/
  Traits/
  Actions/
database/
  migrations/
  seeders/
routes/
  api.php
docs/
  CONTEXTO_PROYECTO.md
```
