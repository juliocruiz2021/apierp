<?php

return [
    'porcentaje_impuesto' => (float) env('ERP_PORCENTAJE_IMPUESTO', 13),
    'prefijo_factura' => env('ERP_PREFIJO_FACTURA', 'FAC'),
    'paginacion' => (int) env('ERP_PAGINACION', 15),
];
