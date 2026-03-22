<?php

namespace App\Http\Requests;

class LoginRequest extends SolicitudApi
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'correo electr\u00f3nico',
            'password' => 'contrase\u00f1a',
        ];
    }
}
