<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateUsuarioRequest extends SolicitudApi
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->route('id'))],
            'password' => $this->reglaContrasena(false),
            'estado' => ['sometimes', ...$this->reglaEstado()],
            'roles' => ['sometimes', 'required', 'array', 'min:1'],
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
