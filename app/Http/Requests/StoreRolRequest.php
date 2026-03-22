<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class StoreRolRequest extends SolicitudApi
{
    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255', Rule::unique('roles', 'nombre')],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'estado' => $this->reglaEstado(),
            'permisos' => ['nullable', 'array'],
            'permisos.*' => ['integer', 'distinct', Rule::exists('permisos', 'id')],
        ];
    }
}
