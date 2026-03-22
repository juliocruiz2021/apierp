<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class StoreFacturaRequest extends SolicitudApi
{
    public function rules(): array
    {
        return [
            'cliente_id' => ['required', 'integer', Rule::exists('clientes', 'id')],
            'fecha' => ['nullable', 'date'],
            'observaciones' => ['nullable', 'string', 'max:1000'],
            'detalle' => ['required', 'array', 'min:1'],
            'detalle.*.producto_id' => ['required', 'integer', 'distinct', Rule::exists('productos', 'id')],
            'detalle.*.cantidad' => ['required', 'numeric', 'gt:0'],
            'detalle.*.precio_unitario' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function attributes(): array
    {
        return [
            'cliente_id' => 'cliente',
            'fecha' => 'fecha',
            'observaciones' => 'observaciones',
            'detalle' => 'detalle de factura',
            'detalle.*.producto_id' => 'producto',
            'detalle.*.cantidad' => 'cantidad',
            'detalle.*.precio_unitario' => 'precio unitario',
        ];
    }
}
