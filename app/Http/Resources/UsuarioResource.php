<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->name,
            'email' => $this->email,
            'estado' => $this->estado,
            'roles' => RolResource::collection($this->whenLoaded('roles')),
            'permisos' => PermisoResource::collection($this->whenLoaded('roles', fn () => $this->permisos())),
            'creado_en' => $this->created_at?->toDateTimeString(),
            'actualizado_en' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
