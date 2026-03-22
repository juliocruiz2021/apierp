<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
            'stock' => $this->stock,
            'stock_minimo' => $this->stock_minimo,
            'estado' => $this->estado,
            'creado_en' => $this->created_at?->toDateTimeString(),
            'actualizado_en' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
