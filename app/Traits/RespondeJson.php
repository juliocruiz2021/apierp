<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait RespondeJson
{
    protected function respuestaExitosa(string $mensaje, mixed $datos = null, int $codigo = 200): JsonResponse
    {
        $respuesta = [
            'exito' => true,
            'mensaje' => $mensaje,
        ];

        if (! is_null($datos)) {
            $respuesta['datos'] = $datos;
        }

        return response()->json($respuesta, $codigo);
    }

    protected function respuestaError(string $mensaje, mixed $errores = null, int $codigo = 400): JsonResponse
    {
        $respuesta = [
            'exito' => false,
            'mensaje' => $mensaje,
        ];

        if (! is_null($errores)) {
            $respuesta['errores'] = $errores;
        }

        return response()->json($respuesta, $codigo);
    }
}
