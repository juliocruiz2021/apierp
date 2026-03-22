<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClienteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'tipo_documento' => $this->tipo_documento,
            'numero_documento' => $this->numero_documento,
            'telefono' => $this->telefono,
            'correo' => $this->correo,
            'direccion' => $this->direccion,
            'estado' => $this->estado,
            'creado_en' => $this->created_at?->toDateTimeString(),
            'actualizado_en' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
