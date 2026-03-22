<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFacturaRequest;
use App\Http\Resources\FacturaResource;
use App\Models\Factura;
use App\Services\FacturaService;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    public function __construct(
        private readonly FacturaService $facturaService
    ) {
    }

    public function index(Request $request)
    {
        $facturas = Factura::query()
            ->with(['cliente', 'usuario.roles.permisos', 'detalles.producto'])
            ->orderByDesc('id')
            ->paginate($this->obtenerPorPagina($request));

        return $this->respuestaExitosa(
            'Facturas obtenidas correctamente.',
            $this->datosPaginados($facturas, FacturaResource::collection($facturas->getCollection()))
        );
    }

    public function store(StoreFacturaRequest $request)
    {
        $factura = $this->facturaService->crear($request->validated(), $request->user());

        return $this->respuestaExitosa('Factura creada correctamente.', new FacturaResource($factura), 201);
    }

    public function show(int $id)
    {
        $factura = Factura::query()
            ->with(['cliente', 'usuario.roles.permisos', 'detalles.producto'])
            ->findOrFail($id);

        return $this->respuestaExitosa('Factura obtenida correctamente.', new FacturaResource($factura));
    }
}
