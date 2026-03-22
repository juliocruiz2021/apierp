<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class StoreProductoRequest extends SolicitudApi
{
    public function rules(): array
    {
        return [
            'codigo' => ['required', 'string', 'max:50', Rule::unique('productos', 'codigo')],
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'precio' => ['required', 'numeric', 'min:0'],
            'stock' => ['nullable', 'numeric', 'min:0'],
            'stock_minimo' => ['nullable', 'numeric', 'min:0'],
            'estado' => $this->reglaEstado(),
        ];
    }
}
