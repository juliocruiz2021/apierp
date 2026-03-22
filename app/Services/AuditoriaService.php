<?php

namespace App\Services;

use App\Models\Auditoria;
use App\Models\User;

class AuditoriaService
{
    public function registrar(
        string $accion,
        string $tabla,
        ?int $registroId = null,
        ?string $descripcion = null,
        ?array $datosAnteriores = null,
        ?array $datosNuevos = null,
        ?User $usuario = null
    ): Auditoria {
        $solicitud = app()->bound('request') ? request() : null;
        $usuarioAutenticado = $usuario ?? auth()->user();

        return Auditoria::query()->create([
            'user_id' => $usuarioAutenticado?->id,
            'accion' => $accion,
            'tabla' => $tabla,
            'registro_id' => $registroId,
            'descripcion' => $descripcion,
            'datos_anteriores' => $this->limpiarDatosSensibles($datosAnteriores),
            'datos_nuevos' => $this->limpiarDatosSensibles($datosNuevos),
            'ip' => $solicitud?->ip(),
            'user_agent' => $solicitud?->userAgent(),
        ]);
    }

    private function limpiarDatosSensibles(?array $datos): ?array
    {
        if (is_null($datos)) {
            return null;
        }

        $clavesSensibles = ['password', 'password_confirmation', 'token'];
        $resultado = [];

        foreach ($datos as $clave => $valor) {
            if (in_array((string) $clave, $clavesSensibles, true)) {
                $resultado[$clave] = '[oculto]';
                continue;
            }

            $resultado[$clave] = is_array($valor)
                ? $this->limpiarDatosSensibles($valor)
                : $valor;
        }

        return $resultado;
    }
}
