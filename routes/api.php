<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\FacturaController;
use App\Http\Controllers\Api\MovimientoInventarioController;
use App\Http\Controllers\Api\PermisoController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\RolController;
use App\Http\Controllers\Api\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/usuarios', [UsuarioController::class, 'index'])->middleware('permiso:usuarios.ver');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->middleware('permiso:usuarios.crear');
    Route::get('/usuarios/{id}', [UsuarioController::class, 'show'])->middleware('permiso:usuarios.ver');
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->middleware('permiso:usuarios.actualizar');

    Route::get('/roles', [RolController::class, 'index'])->middleware('permiso:roles.ver');
    Route::post('/roles', [RolController::class, 'store'])->middleware('permiso:roles.crear');
    Route::get('/roles/{id}', [RolController::class, 'show'])->middleware('permiso:roles.ver');
    Route::put('/roles/{id}', [RolController::class, 'update'])->middleware('permiso:roles.actualizar');

    Route::get('/permisos', [PermisoController::class, 'index'])->middleware('permiso:permisos.ver');
    Route::post('/permisos', [PermisoController::class, 'store'])->middleware('permiso:permisos.crear');
    Route::get('/permisos/{id}', [PermisoController::class, 'show'])->middleware('permiso:permisos.ver');
    Route::put('/permisos/{id}', [PermisoController::class, 'update'])->middleware('permiso:permisos.actualizar');

    Route::get('/clientes', [ClienteController::class, 'index'])->middleware('permiso:clientes.ver');
    Route::post('/clientes', [ClienteController::class, 'store'])->middleware('permiso:clientes.crear');
    Route::get('/clientes/{id}', [ClienteController::class, 'show'])->middleware('permiso:clientes.ver');
    Route::put('/clientes/{id}', [ClienteController::class, 'update'])->middleware('permiso:clientes.actualizar');

    Route::get('/productos', [ProductoController::class, 'index'])->middleware('permiso:productos.ver');
    Route::post('/productos', [ProductoController::class, 'store'])->middleware('permiso:productos.crear');
    Route::get('/productos/{id}', [ProductoController::class, 'show'])->middleware('permiso:productos.ver');
    Route::put('/productos/{id}', [ProductoController::class, 'update'])->middleware('permiso:productos.actualizar');

    Route::get('/facturas', [FacturaController::class, 'index'])->middleware('permiso:facturas.ver');
    Route::post('/facturas', [FacturaController::class, 'store'])->middleware('permiso:facturas.crear');
    Route::get('/facturas/{id}', [FacturaController::class, 'show'])->middleware('permiso:facturas.ver');

    Route::get('/movimientos-inventario', [MovimientoInventarioController::class, 'index'])
        ->middleware('permiso:movimientos_inventario.ver');
});
