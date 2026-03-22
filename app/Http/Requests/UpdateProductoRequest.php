<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateProductoRequest extends SolicitudApi
{
    public function rules(): array
    {
        return [
            'codigo' => ['sometimes', 'required', 'string', 'max:50', Rule::unique('productos', 'codigo')->ignore($this->route('id'))],
            'nombre' => ['sometimes', 'required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'precio' => ['sometimes', 'required', 'numeric', 'min:0'],
            'stock' => ['sometimes', 'required', 'numeric', 'min:0'],
            'stock_minimo' => ['sometimes', 'required', 'numeric', 'min:0'],
            'estado' => ['sometimes', ...$this->reglaEstado()],
        ];
    }
}
