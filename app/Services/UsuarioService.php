<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UsuarioService
{
    public function __construct(
        private readonly AuditoriaService $auditoriaService
    ) {
    }

    public function crear(array $datos, User $actor): User
    {
        return DB::transaction(function () use ($datos, $actor) {
            $usuario = User::query()->create([
                'name' => trim($datos['name']),
                'email' => strtolower(trim($datos['email'])),
                'password' => $datos['password'],
                'estado' => $datos['estado'],
            ]);

            $usuario->roles()->sync($datos['roles']);
            $usuario->load('roles.permisos');

            $this->auditoriaService->registrar(
                accion: 'crear',
                tabla: 'users',
                registroId: $usuario->id,
                descripcion: 'Usuario creado correctamente.',
                datosNuevos: $usuario->toArray(),
                usuario: $actor
            );

            return $usuario;
        });
    }

    public function actualizar(User $usuario, array $datos, User $actor): User
    {
        return DB::transaction(function () use ($usuario, $datos, $actor) {
            $datosAnteriores = $usuario->load('roles.permisos')->toArray();
            $datosActualizar = Arr::except($datos, ['roles']);

            if (array_key_exists('email', $datosActualizar)) {
                $datosActualizar['email'] = strtolower(trim($datosActualizar['email']));
            }

            if (array_key_exists('name', $datosActualizar)) {
                $datosActualizar['name'] = trim($datosActualizar['name']);
            }

            if (empty($datosActualizar['password'] ?? null)) {
                unset($datosActualizar['password']);
            }

            $usuario->fill($datosActualizar);
            $usuario->save();

            if (array_key_exists('roles', $datos)) {
                $usuario->roles()->sync($datos['roles']);
            }

            $usuario->load('roles.permisos');

            $this->auditoriaService->registrar(
                accion: 'actualizar',
                tabla: 'users',
                registroId: $usuario->id,
                descripcion: 'Usuario actualizado correctamente.',
                datosAnteriores: $datosAnteriores,
                datosNuevos: $usuario->toArray(),
                usuario: $actor
            );

            return $usuario;
        });
    }
}
