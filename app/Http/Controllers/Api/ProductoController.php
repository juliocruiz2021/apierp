<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Http\Resources\ProductoResource;
use App\Models\Producto;
use App\Services\ProductoService;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function __construct(
        private readonly ProductoService $productoService
    ) {
    }

    public function index(Request $request)
    {
        $productos = Producto::query()
            ->orderBy('id')
            ->paginate($this->obtenerPorPagina($request));

        return $this->respuestaExitosa(
            'Productos obtenidos correctamente.',
            $this->datosPaginados($productos, ProductoResource::collection($productos->getCollection()))
        );
    }

    public function store(StoreProductoRequest $request)
    {
        $producto = $this->productoService->crear($request->validated(), $request->user());

        return $this->respuestaExitosa('Producto creado correctamente.', new ProductoResource($producto), 201);
    }

    public function show(int $id)
    {
        $producto = Producto::query()->findOrFail($id);

        return $this->respuestaExitosa('Producto obtenido correctamente.', new ProductoResource($producto));
    }

    public function update(UpdateProductoRequest $request, int $id)
    {
        $producto = Producto::query()->findOrFail($id);
        $producto = $this->productoService->actualizar($producto, $request->validated(), $request->user());

        return $this->respuestaExitosa('Producto actualizado correctamente.', new ProductoResource($producto));
    }
}
