<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRolRequest;
use App\Http\Requests\UpdateRolRequest;
use App\Http\Resources\RolResource;
use App\Models\Rol;
use App\Services\RolService;
use Illuminate\Http\Request;

class RolController extends Controller
{
    public function __construct(
        private readonly RolService $rolService
    ) {
    }

    public function index(Request $request)
    {
        $roles = Rol::query()
            ->with('permisos')
            ->orderBy('id')
            ->paginate($this->obtenerPorPagina($request));

        return $this->respuestaExitosa(
            'Roles obtenidos correctamente.',
            $this->datosPaginados($roles, RolResource::collection($roles->getCollection()))
        );
    }

    public function store(StoreRolRequest $request)
    {
        $rol = $this->rolService->crear($request->validated(), $request->user());

        return $this->respuestaExitosa('Rol creado correctamente.', new RolResource($rol), 201);
    }

    public function show(int $id)
    {
        $rol = Rol::query()->with('permisos')->findOrFail($id);

        return $this->respuestaExitosa('Rol obtenido correctamente.', new RolResource($rol));
    }

    public function update(UpdateRolRequest $request, int $id)
    {
        $rol = Rol::query()->findOrFail($id);
        $rol = $this->rolService->actualizar($rol, $request->validated(), $request->user());

        return $this->respuestaExitosa('Rol actualizado correctamente.', new RolResource($rol));
    }
}
