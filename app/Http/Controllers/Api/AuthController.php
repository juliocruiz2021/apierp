<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UsuarioResource;
use App\Services\ServicioAutenticacion;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly ServicioAutenticacion $servicioAutenticacion
    ) {
    }

    public function login(LoginRequest $request)
    {
        $resultado = $this->servicioAutenticacion->iniciarSesion($request->validated());

        return $this->respuestaExitosa('Inicio de sesi\u00f3n realizado correctamente.', [
            'token' => $resultado['token'],
            'tipo_token' => $resultado['tipo_token'],
            'usuario' => new UsuarioResource($resultado['usuario']),
        ]);
    }

    public function logout(Request $request)
    {
        $this->servicioAutenticacion->cerrarSesion($request->user());

        return $this->respuestaExitosa('Cierre de sesi\u00f3n realizado correctamente.');
    }

    public function me(Request $request)
    {
        $usuario = $this->servicioAutenticacion->obtenerPerfil($request->user());

        return $this->respuestaExitosa('Perfil obtenido correctamente.', new UsuarioResource($usuario));
    }
}
