<?php

namespace App\Services;

use App\Models\Permiso;
use App\Models\User;

class PermisoService
{
    public function __construct(
        private readonly AuditoriaService $auditoriaService
    ) {
    }

    public function crear(array $datos, User $actor): Permiso
    {
        $permiso = Permiso::query()->create([
            'nombre' => trim($datos['nombre']),
            'clave' => trim($datos['clave']),
            'descripcion' => $datos['descripcion'] ?? null,
            'estado' => $datos['estado'],
        ]);

        $this->auditoriaService->registrar(
            accion: 'crear',
            tabla: 'permisos',
            registroId: $permiso->id,
            descripcion: 'Permiso creado correctamente.',
            datosNuevos: $permiso->toArray(),
            usuario: $actor
        );

        return $permiso;
    }

    public function actualizar(Permiso $permiso, array $datos, User $actor): Permiso
    {
        $datosAnteriores = $permiso->toArray();

        $permiso->fill([
            'nombre' => array_key_exists('nombre', $datos) ? trim($datos['nombre']) : $permiso->nombre,
            'clave' => array_key_exists('clave', $datos) ? trim($datos['clave']) : $permiso->clave,
            'descripcion' => array_key_exists('descripcion', $datos) ? $datos['descripcion'] : $permiso->descripcion,
            'estado' => $datos['estado'] ?? $permiso->estado,
        ]);
        $permiso->save();

        $this->auditoriaService->registrar(
            accion: 'actualizar',
            tabla: 'permisos',
            registroId: $permiso->id,
            descripcion: 'Permiso actualizado correctamente.',
            datosAnteriores: $datosAnteriores,
            datosNuevos: $permiso->toArray(),
            usuario: $actor
        );

        return $permiso;
    }
}
