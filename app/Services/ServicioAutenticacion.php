<?php

namespace App\Services;

use App\Enums\EstadoRegistroEnum;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ServicioAutenticacion
{
    public function __construct(
        private readonly AuditoriaService $auditoriaService
    ) {
    }

    public function iniciarSesion(array $credenciales): array
    {
        $correo = strtolower(trim($credenciales['email']));

        $usuario = User::query()
            ->with('roles.permisos')
            ->where('email', $correo)
            ->first();

        if (! $usuario || ! Hash::check($credenciales['password'], $usuario->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas no son v\u00e1lidas.'],
            ]);
        }

        if ($usuario->estado !== EstadoRegistroEnum::ACTIVO->value) {
            throw ValidationException::withMessages([
                'email' => ['El usuario est\u00e1 inactivo y no puede iniciar sesi\u00f3n.'],
            ]);
        }

        $usuario->tokens()->delete();

        $expiracion = config('sanctum.expiration');
        $token = $usuario->createToken(
            'api-erp',
            ['*'],
            $expiracion ? now()->addMinutes((int) $expiracion) : null
        )->plainTextToken;

        $this->auditoriaService->registrar(
            accion: 'login',
            tabla: 'users',
            registroId: $usuario->id,
            descripcion: 'Inicio de sesi\u00f3n exitoso.',
            datosNuevos: ['email' => $usuario->email],
            usuario: $usuario
        );

        return [
            'token' => $token,
            'tipo_token' => 'Bearer',
            'usuario' => $usuario->fresh()->load('roles.permisos'),
        ];
    }

    public function cerrarSesion(User $usuario): void
    {
        $tokenActual = $usuario->currentAccessToken();

        if ($tokenActual) {
            $tokenActual->delete();
        } else {
            $usuario->tokens()->delete();
        }

        $this->auditoriaService->registrar(
            accion: 'logout',
            tabla: 'users',
            registroId: $usuario->id,
            descripcion: 'Cierre de sesi\u00f3n exitoso.',
            datosNuevos: ['email' => $usuario->email],
            usuario: $usuario
        );
    }

    public function obtenerPerfil(User $usuario): User
    {
        return $usuario->load('roles.permisos');
    }
}
