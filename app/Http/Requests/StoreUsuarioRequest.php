<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class StoreUsuarioRequest extends SolicitudApi
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => $this->reglaContrasena(),
            'estado' => $this->reglaEstado(),
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['integer', 'distinct', Rule::exists('roles', 'id')],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'email' => 'correo electr\u00f3nico',
            'password' => 'contrase\u00f1a',
            'estado' => 'estado',
            'roles' => 'roles',
            'roles.*' => 'rol',
        ];
    }
}
