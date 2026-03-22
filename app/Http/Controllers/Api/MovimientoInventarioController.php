<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MovimientoInventarioResource;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;

class MovimientoInventarioController extends Controller
{
    public function index(Request $request)
    {
        $movimientos = MovimientoInventario::query()
            ->with('producto')
            ->orderByDesc('id')
            ->paginate($this->obtenerPorPagina($request));

        return $this->respuestaExitosa(
            'Movimientos de inventario obtenidos correctamente.',
            $this->datosPaginados($movimientos, MovimientoInventarioResource::collection($movimientos->getCollection()))
        );
    }
}
