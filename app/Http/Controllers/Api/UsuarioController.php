<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Http\Resources\UsuarioResource;
use App\Models\User;
use App\Services\UsuarioService;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function __construct(
        private readonly UsuarioService $usuarioService
    ) {
    }

    public function index(Request $request)
    {
        $usuarios = User::query()
            ->with('roles.permisos')
            ->orderBy('id')
            ->paginate($this->obtenerPorPagina($request));

        return $this->respuestaExitosa(
            'Usuarios obtenidos correctamente.',
            $this->datosPaginados($usuarios, UsuarioResource::collection($usuarios->getCollection()))
        );
    }

    public function store(StoreUsuarioRequest $request)
    {
        $usuario = $this->usuarioService->crear($request->validated(), $request->user());

        return $this->respuestaExitosa('Usuario creado correctamente.', new UsuarioResource($usuario), 201);
    }

    public function show(int $id)
    {
        $usuario = User::query()->with('roles.permisos')->findOrFail($id);

        return $this->respuestaExitosa('Usuario obtenido correctamente.', new UsuarioResource($usuario));
    }

    public function update(UpdateUsuarioRequest $request, int $id)
    {
        $usuario = User::query()->findOrFail($id);
        $usuario = $this->usuarioService->actualizar($usuario, $request->validated(), $request->user());

        return $this->respuestaExitosa('Usuario actualizado correctamente.', new UsuarioResource($usuario));
    }
}
