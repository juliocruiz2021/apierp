<?php

namespace App\Services;

use App\Models\Producto;
use App\Models\User;

class ProductoService
{
    public function __construct(
        private readonly AuditoriaService $auditoriaService
    ) {
    }

    public function crear(array $datos, User $actor): Producto
    {
        $producto = Producto::query()->create([
            'codigo' => trim($datos['codigo']),
            'nombre' => trim($datos['nombre']),
            'descripcion' => $datos['descripcion'] ?? null,
            'precio' => $datos['precio'],
            'stock' => $datos['stock'] ?? 0,
            'stock_minimo' => $datos['stock_minimo'] ?? 0,
            'estado' => $datos['estado'],
        ]);

        $this->auditoriaService->registrar(
            accion: 'crear',
            tabla: 'productos',
            registroId: $producto->id,
            descripcion: 'Producto creado correctamente.',
            datosNuevos: $producto->toArray(),
            usuario: $actor
        );

        return $producto;
    }

    public function actualizar(Producto $producto, array $datos, User $actor): Producto
    {
        $datosAnteriores = $producto->toArray();

        $producto->fill([
            'codigo' => array_key_exists('codigo', $datos) ? trim($datos['codigo']) : $producto->codigo,
            'nombre' => array_key_exists('nombre', $datos) ? trim($datos['nombre']) : $producto->nombre,
            'descripcion' => array_key_exists('descripcion', $datos) ? $datos['descripcion'] : $producto->descripcion,
            'precio' => $datos['precio'] ?? $producto->precio,
            'stock' => $datos['stock'] ?? $producto->stock,
            'stock_minimo' => $datos['stock_minimo'] ?? $producto->stock_minimo,
            'estado' => $datos['estado'] ?? $producto->estado,
        ]);
        $producto->save();

        $this->auditoriaService->registrar(
            accion: 'actualizar',
            tabla: 'productos',
            registroId: $producto->id,
            descripcion: 'Producto actualizado correctamente.',
            datosAnteriores: $datosAnteriores,
            datosNuevos: $producto->toArray(),
            usuario: $actor
        );

        return $producto;
    }
}
