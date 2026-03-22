<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermisoRequest;
use App\Http\Requests\UpdatePermisoRequest;
use App\Http\Resources\PermisoResource;
use App\Models\Permiso;
use App\Services\PermisoService;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    public function __construct(
        private readonly PermisoService $permisoService
    ) {
    }

    public function index(Request $request)
    {
        $permisos = Permiso::query()
            ->orderBy('id')
            ->paginate($this->obtenerPorPagina($request));

        return $this->respuestaExitosa(
            'Permisos obtenidos correctamente.',
            $this->datosPaginados($permisos, PermisoResource::collection($permisos->getCollection()))
        );
    }

    public function store(StorePermisoRequest $request)
    {
        $permiso = $this->permisoService->crear($request->validated(), $request->user());

        return $this->respuestaExitosa('Permiso creado correctamente.', new PermisoResource($permiso), 201);
    }

    public function show(int $id)
    {
        $permiso = Permiso::query()->findOrFail($id);

        return $this->respuestaExitosa('Permiso obtenido correctamente.', new PermisoResource($permiso));
    }

    public function update(UpdatePermisoRequest $request, int $id)
    {
        $permiso = Permiso::query()->findOrFail($id);
        $permiso = $this->permisoService->actualizar($permiso, $request->validated(), $request->user());

        return $this->respuestaExitosa('Permiso actualizado correctamente.', new PermisoResource($permiso));
    }
}
