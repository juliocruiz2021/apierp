<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Http\Resources\ClienteResource;
use App\Models\Cliente;
use App\Services\ClienteService;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function __construct(
        private readonly ClienteService $clienteService
    ) {
    }

    public function index(Request $request)
    {
        $clientes = Cliente::query()
            ->orderBy('id')
            ->paginate($this->obtenerPorPagina($request));

        return $this->respuestaExitosa(
            'Clientes obtenidos correctamente.',
            $this->datosPaginados($clientes, ClienteResource::collection($clientes->getCollection()))
        );
    }

    public function store(StoreClienteRequest $request)
    {
        $cliente = $this->clienteService->crear($request->validated(), $request->user());

        return $this->respuestaExitosa('Cliente creado correctamente.', new ClienteResource($cliente), 201);
    }

    public function show(int $id)
    {
        $cliente = Cliente::query()->findOrFail($id);

        return $this->respuestaExitosa('Cliente obtenido correctamente.', new ClienteResource($cliente));
    }

    public function update(UpdateClienteRequest $request, int $id)
    {
        $cliente = Cliente::query()->findOrFail($id);
        $cliente = $this->clienteService->actualizar($cliente, $request->validated(), $request->user());

        return $this->respuestaExitosa('Cliente actualizado correctamente.', new ClienteResource($cliente));
    }
}
