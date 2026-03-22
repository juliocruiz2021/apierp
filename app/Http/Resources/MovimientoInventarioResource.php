<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovimientoInventarioResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tipo' => $this->tipo,
            'cantidad' => $this->cantidad,
            'stock_antes' => $this->stock_antes,
            'stock_despues' => $this->stock_despues,
            'referencia_tipo' => $this->referencia_tipo,
            'referencia_id' => $this->referencia_id,
            'observaciones' => $this->observaciones,
            'producto' => new ProductoResource($this->whenLoaded('producto')),
            'creado_en' => $this->created_at?->toDateTimeString(),
        ];
    }
}
