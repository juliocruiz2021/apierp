<?php

namespace App\Services;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RolService
{
    public function __construct(
        private readonly AuditoriaService $auditoriaService
    ) {
    }

    public function crear(array $datos, User $actor): Rol
    {
        return DB::transaction(function () use ($datos, $actor) {
            $rol = Rol::query()->create([
                'nombre' => trim($datos['nombre']),
                'descripcion' => $datos['descripcion'] ?? null,
                'estado' => $datos['estado'],
            ]);

            $rol->permisos()->sync($datos['permisos'] ?? []);
            $rol->load('permisos');

            $this->auditoriaService->registrar(
                accion: 'crear',
                tabla: 'roles',
                registroId: $rol->id,
                descripcion: 'Rol creado correctamente.',
                datosNuevos: $rol->toArray(),
                usuario: $actor
            );

            return $rol;
        });
    }

    public function actualizar(Rol $rol, array $datos, User $actor): Rol
    {
        return DB::transaction(function () use ($rol, $datos, $actor) {
            $datosAnteriores = $rol->load('permisos')->toArray();

            $rol->fill([
                'nombre' => array_key_exists('nombre', $datos) ? trim($datos['nombre']) : $rol->nombre,
                'descripcion' => array_key_exists('descripcion', $datos) ? $datos['descripcion'] : $rol->descripcion,
                'estado' => $datos['estado'] ?? $rol->estado,
            ]);
            $rol->save();

            if (array_key_exists('permisos', $datos)) {
                $rol->permisos()->sync($datos['permisos'] ?? []);
            }

            $rol->load('permisos');

            $this->auditoriaService->registrar(
                accion: 'actualizar',
                tabla: 'roles',
                registroId: $rol->id,
                descripcion: 'Rol actualizado correctamente.',
                datosAnteriores: $datosAnteriores,
                datosNuevos: $rol->toArray(),
                usuario: $actor
            );

            return $rol;
        });
    }
}
