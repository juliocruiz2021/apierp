# Contexto del Proyecto API ERP

## Objetivo

Construir una base profesional de ERP API v1 dentro de este repositorio con Laravel, PostgreSQL y Sanctum, dejando la arquitectura lista para crecer sin mezclar lógica de negocio en controladores.

## Estado actual

- Base Laravel creada dentro de `d:\desarrollo_laravel\apierp`
- Sanctum instalado y configurado
- API REST registrada en `routes/api.php`
- Migraciones ERP creadas
- Modelos y relaciones Eloquent configurados
- Requests con validaciones estrictas en español
- Services y Actions implementados
- Middleware de permisos implementado
- Controladores API creados
- Seeders base creados
- README operativo en español creado
- Repositorio Git inicializado localmente y remoto GitHub creado para `juliocruiz2021/apierp`

## Decisiones técnicas clave

- Autenticación por tokens con Laravel Sanctum
- PostgreSQL como motor principal configurado desde `.env`
- Respuestas JSON uniformes en español
- Facturación dentro de transacción con validación de stock
- Re-cálculo de montos en backend
- Roles y permisos propios con pivotes `rol_usuario` y `permiso_rol`
- Auditoría básica persistida en la tabla `auditoria`
- Rate limiting para login y tráfico API general

## Capas implementadas

### Controladores API

- `AuthController`
- `UsuarioController`
- `RolController`
- `PermisoController`
- `ClienteController`
- `ProductoController`
- `FacturaController`
- `MovimientoInventarioController`

### Requests

- `LoginRequest`
- `StoreUsuarioRequest`
- `UpdateUsuarioRequest`
- `StoreRolRequest`
- `UpdateRolRequest`
- `StorePermisoRequest`
- `UpdatePermisoRequest`
- `StoreClienteRequest`
- `UpdateClienteRequest`
- `StoreProductoRequest`
- `UpdateProductoRequest`
- `StoreFacturaRequest`

### Services

- `ServicioAutenticacion`
- `AuditoriaService`
- `UsuarioService`
- `RolService`
- `PermisoService`
- `ClienteService`
- `ProductoService`
- `FacturaService`

### Actions

- `GenerarNumeroFacturaAction`
- `RegistrarMovimientoInventarioAction`

## Flujo crítico de facturación

1. Validar datos con `StoreFacturaRequest`
2. Verificar cliente activo
3. Verificar productos activos
4. Validar stock suficiente
5. Bloquear filas relevantes y abrir transacción
6. Generar número de factura
7. Crear factura y detalles
8. Descontar stock
9. Registrar movimientos de inventario
10. Registrar auditoría
11. Confirmar transacción

## Credenciales base

- Correo: `admin@apierp.local`
- Contraseña: `Admin123!`

## Pendientes operativos normales

- Confirmar credenciales locales de PostgreSQL y ejecutar `php artisan migrate --seed`
- Probar login y creación de factura sobre una base PostgreSQL real
- Verificar acceso correcto del usuario `postgres` en la instancia local

## Intentos recientes

- Se probó la conexión a PostgreSQL con usuario `postgres` y clave `lvhpct43` contra `127.0.0.1:5432`.
- El servidor respondió `FATAL: la autentificación password falló para el usuario "postgres"`.
- Se detectó una cuenta GitHub autenticada en este equipo: `juliocruiz2021`.
- Se creó el repositorio remoto `https://github.com/juliocruiz2021/apierp.git`.
- El proyecto local quedó enlazado al remoto `origin`.

## Nota de secuencia

Este archivo sirve como bitácora viva del proyecto. Si se amplían módulos futuros, la recomendación es documentar aquí:

- cambios de arquitectura
- decisiones de seguridad
- módulos agregados
- riesgos conocidos
- tareas pendientes
