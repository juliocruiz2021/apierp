<?php

namespace App\Enums;

enum TipoMovimientoInventarioEnum: string
{
    case ENTRADA = 'entrada';
    case SALIDA = 'salida';
    case AJUSTE = 'ajuste';
}
