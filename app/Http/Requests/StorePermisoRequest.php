<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class StorePermisoRequest extends SolicitudApi
{
    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'clave' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9._-]+$/', Rule::unique('permisos', 'clave')],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'estado' => $this->reglaEstado(),
        ];
    }

    public function messages(): array
    {
        return [
            'clave.regex' => 'La clave solo puede contener letras min\u00fasculas, n\u00fameros, puntos, guiones y guiones bajos.',
        ];
    }
}
