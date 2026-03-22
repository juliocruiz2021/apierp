<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacturaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'numero' => $this->numero,
            'fecha' => $this->fecha?->format('Y-m-d'),
            'subtotal' => $this->subtotal,
            'impuesto' => $this->impuesto,
            'total' => $this->total,
            'observaciones' => $this->observaciones,
            'estado' => $this->estado,
            'cliente' => new ClienteResource($this->whenLoaded('cliente')),
            'usuario' => new UsuarioResource($this->whenLoaded('usuario')),
            'detalles' => DetalleFacturaResource::collection($this->whenLoaded('detalles')),
            'creado_en' => $this->created_at?->toDateTimeString(),
            'actualizado_en' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
