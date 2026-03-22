<?php

namespace App\Http\Requests;

use App\Rules\ContrasenaSegura;
use Illuminate\Foundation\Http\FormRequest;

abstract class SolicitudApi extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function reglaEstado(): array
    {
        return ['required', 'string', 'in:activo,inactivo'];
    }

    protected function reglaContrasena(bool $obligatoria = true): array
    {
        return [
            $obligatoria ? 'required' : 'nullable',
            'string',
            new ContrasenaSegura(),
        ];
    }
}
