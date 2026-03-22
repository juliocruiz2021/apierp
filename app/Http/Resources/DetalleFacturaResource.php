<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetalleFacturaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cantidad' => $this->cantidad,
            'precio_unitario' => $this->precio_unitario,
            'subtotal_linea' => $this->subtotal_linea,
            'impuesto_linea' => $this->impuesto_linea,
            'total_linea' => $this->total_linea,
            'producto' => new ProductoResource($this->whenLoaded('producto')),
        ];
    }
}
