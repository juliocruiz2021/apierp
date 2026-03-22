<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\User;

class ClienteService
{
    public function __construct(
        private readonly AuditoriaService $auditoriaService
    ) {
    }

    public function crear(array $datos, User $actor): Cliente
    {
        $cliente = Cliente::query()->create([
            'codigo' => trim($datos['codigo']),
            'nombre' => trim($datos['nombre']),
            'tipo_documento' => $datos['tipo_documento'] ?? null,
            'numero_documento' => $datos['numero_documento'] ?? null,
            'telefono' => $datos['telefono'] ?? null,
            'correo' => isset($datos['correo']) ? strtolower(trim($datos['correo'])) : null,
            'direccion' => $datos['direccion'] ?? null,
            'estado' => $datos['estado'],
        ]);

        $this->auditoriaService->registrar(
            accion: 'crear',
            tabla: 'clientes',
            registroId: $cliente->id,
            descripcion: 'Cliente creado correctamente.',
            datosNuevos: $cliente->toArray(),
            usuario: $actor
        );

        return $cliente;
    }

    public function actualizar(Cliente $cliente, array $datos, User $actor): Cliente
    {
        $datosAnteriores = $cliente->toArray();

        $cliente->fill([
            'codigo' => array_key_exists('codigo', $datos) ? trim($datos['codigo']) : $cliente->codigo,
            'nombre' => array_key_exists('nombre', $datos) ? trim($datos['nombre']) : $cliente->nombre,
            'tipo_documento' => array_key_exists('tipo_documento', $datos) ? $datos['tipo_documento'] : $cliente->tipo_documento,
            'numero_documento' => array_key_exists('numero_documento', $datos) ? $datos['numero_documento'] : $cliente->numero_documento,
            'telefono' => array_key_exists('telefono', $datos) ? $datos['telefono'] : $cliente->telefono,
            'correo' => array_key_exists('correo', $datos) ? (filled($datos['correo']) ? strtolower(trim($datos['correo'])) : null) : $cliente->correo,
            'direccion' => array_key_exists('direccion', $datos) ? $datos['direccion'] : $cliente->direccion,
            'estado' => $datos['estado'] ?? $cliente->estado,
        ]);
        $cliente->save();

        $this->auditoriaService->registrar(
            accion: 'actualizar',
            tabla: 'clientes',
            registroId: $cliente->id,
            descripcion: 'Cliente actualizado correctamente.',
            datosAnteriores: $datosAnteriores,
            datosNuevos: $cliente->toArray(),
            usuario: $actor
        );

        return $cliente;
    }
}
