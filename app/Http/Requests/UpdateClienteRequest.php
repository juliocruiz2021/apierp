<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateClienteRequest extends SolicitudApi
{
    public function rules(): array
    {
        return [
            'codigo' => ['sometimes', 'required', 'string', 'max:50', Rule::unique('clientes', 'codigo')->ignore($this->route('id'))],
            'nombre' => ['sometimes', 'required', 'string', 'max:255'],
            'tipo_documento' => ['nullable', 'string', 'max:50'],
            'numero_documento' => ['nullable', 'string', 'max:50'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'correo' => ['nullable', 'email', 'max:255'],
            'direccion' => ['nullable', 'string', 'max:1000'],
            'estado' => ['sometimes', ...$this->reglaEstado()],
        ];
    }
}
