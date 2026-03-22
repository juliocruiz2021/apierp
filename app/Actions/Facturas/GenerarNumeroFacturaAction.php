<?php

namespace App\Actions\Facturas;

use App\Models\Factura;

class GenerarNumeroFacturaAction
{
    public function ejecutar(): string
    {
        $ultimoNumero = Factura::query()
            ->lockForUpdate()
            ->orderByDesc('id')
            ->value('numero');

        $secuencia = 1;

        if ($ultimoNumero && preg_match('/(\d+)$/', $ultimoNumero, $coincidencias)) {
            $secuencia = ((int) $coincidencias[1]) + 1;
        }

        return sprintf('%s-%08d', config('erp.prefijo_factura', 'FAC'), $secuencia);
    }
}
