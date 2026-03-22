<?php

namespace App\Actions\Inventario;

use App\Models\MovimientoInventario;
use App\Models\Producto;

class RegistrarMovimientoInventarioAction
{
    public function ejecutar(
        Producto $producto,
        string $tipo,
        string $cantidad,
        string $stockAntes,
        string $stockDespues,
        string $referenciaTipo,
        int $referenciaId,
        ?string $observaciones = null
    ): MovimientoInventario {
        return MovimientoInventario::query()->create([
            'producto_id' => $producto->id,
            'tipo' => $tipo,
            'cantidad' => $cantidad,
            'stock_antes' => $stockAntes,
            'stock_despues' => $stockDespues,
            'referencia_tipo' => $referenciaTipo,
            'referencia_id' => $referenciaId,
            'observaciones' => $observaciones,
        ]);
    }
}
