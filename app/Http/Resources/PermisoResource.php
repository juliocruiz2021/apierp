<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermisoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'clave' => $this->clave,
            'descripcion' => $this->descripcion,
            'estado' => $this->estado,
            'creado_en' => $this->created_at?->toDateTimeString(),
            'actualizado_en' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
