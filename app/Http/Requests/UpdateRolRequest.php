<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateRolRequest extends SolicitudApi
{
    public function rules(): array
    {
        return [
            'nombre' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('roles', 'nombre')->ignore($this->route('id'))],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'estado' => ['sometimes', ...$this->reglaEstado()],
            'permisos' => ['sometimes', 'nullable', 'array'],
            'permisos.*' => ['integer', 'distinct', Rule::exists('permisos', 'id')],
        ];
    }
}
