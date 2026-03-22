<?php

namespace App\Http\Controllers;

use App\Traits\RespondeJson;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class Controller
{
    use RespondeJson;

    protected function obtenerPorPagina(Request $request): int
    {
        return max(1, min($request->integer('por_pagina', config('erp.paginacion')), 100));
    }

    protected function datosPaginados(LengthAwarePaginator $paginador, mixed $items): array
    {
        return [
            'items' => $items,
            'paginacion' => [
                'pagina_actual' => $paginador->currentPage(),
                'por_pagina' => $paginador->perPage(),
                'total' => $paginador->total(),
                'ultima_pagina' => $paginador->lastPage(),
            ],
        ];
    }
}
