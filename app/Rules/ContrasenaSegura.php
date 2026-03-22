<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ContrasenaSegura implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $contrasena = (string) $value;

        $esValida = strlen($contrasena) >= 8
            && preg_match('/[a-z]/', $contrasena)
            && preg_match('/[A-Z]/', $contrasena)
            && preg_match('/[0-9]/', $contrasena)
            && preg_match('/[^A-Za-z0-9]/', $contrasena);

        if (! $esValida) {
            $fail('La contraseña debe tener al menos 8 caracteres e incluir mayúsculas, minúsculas, números y símbolos.');
        }
    }
}
