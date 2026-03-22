<?php

namespace App\Services;

use App\Actions\Facturas\GenerarNumeroFacturaAction;
use App\Actions\Inventario\RegistrarMovimientoInventarioAction;
use App\Enums\EstadoFacturaEnum;
use App\Enums\EstadoRegistroEnum;
use App\Enums\TipoMovimientoInventarioEnum;
use App\Models\Cliente;
use App\Models\Factura;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FacturaService
{
    public function __construct(
        private readonly AuditoriaService $auditoriaService,
        private readonly GenerarNumeroFacturaAction $generarNumeroFacturaAction,
        private readonly RegistrarMovimientoInventarioAction $registrarMovimientoInventarioAction
    ) {
    }

    public function crear(array $datos, User $usuario): Factura
    {
        return DB::transaction(function () use ($datos, $usuario) {
            $cliente = Cliente::query()->lockForUpdate()->find($datos['cliente_id']);

            if (! $cliente) {
                throw ValidationException::withMessages([
                    'cliente_id' => ['El cliente seleccionado no existe.'],
                ]);
            }

            if ($cliente->estado !== EstadoRegistroEnum::ACTIVO->value) {
                throw ValidationException::withMessages([
                    'cliente_id' => ['No se puede facturar a un cliente inactivo.'],
                ]);
            }

            $lineas = collect($datos['detalle'] ?? []);

            if ($lineas->isEmpty()) {
                throw ValidationException::withMessages([
                    'detalle' => ['La factura debe contener al menos una l\u00ednea.'],
                ]);
            }

            $productos = Producto::query()
                ->whereIn('id', $lineas->pluck('producto_id')->unique()->all())
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $tasaImpuesto = $this->decimal(config('erp.porcentaje_impuesto', 13));
            $subtotalFactura = '0.00';
            $impuestoFactura = '0.00';
            $totalFactura = '0.00';
            $detallesPreparados = [];

            foreach ($lineas as $indice => $linea) {
                $producto = $productos->get((int) $linea['producto_id']);

                if (! $producto) {
                    throw ValidationException::withMessages([
                        "detalle.$indice.producto_id" => ['El producto seleccionado no existe.'],
                    ]);
                }

                if ($producto->estado !== EstadoRegistroEnum::ACTIVO->value) {
                    throw ValidationException::withMessages([
                        "detalle.$indice.producto_id" => ['No se puede facturar un producto inactivo.'],
                    ]);
                }

                $cantidad = $this->decimal($linea['cantidad']);

                if (bccomp($cantidad, '0.00', 2) <= 0) {
                    throw ValidationException::withMessages([
                        "detalle.$indice.cantidad" => ['La cantidad debe ser mayor que cero.'],
                    ]);
                }

                $stockActual = $this->decimal($producto->stock);

                if (bccomp($stockActual, $cantidad, 2) < 0) {
                    throw ValidationException::withMessages([
                        "detalle.$indice.cantidad" => ["Stock insuficiente para el producto {$producto->nombre}."],
                    ]);
                }

                $precioUnitario = $this->decimal($producto->precio);
                $subtotalLinea = bcmul($cantidad, $precioUnitario, 2);
                $impuestoLinea = $this->calcularImpuesto($subtotalLinea, $tasaImpuesto);
                $totalLinea = bcadd($subtotalLinea, $impuestoLinea, 2);

                $detallesPreparados[] = [
                    'producto' => $producto,
                    'cantidad' => $cantidad,
                    'stock_antes' => $stockActual,
                    'stock_despues' => bcsub($stockActual, $cantidad, 2),
                    'precio_unitario' => $precioUnitario,
                    'subtotal_linea' => $subtotalLinea,
                    'impuesto_linea' => $impuestoLinea,
                    'total_linea' => $totalLinea,
                ];

                $subtotalFactura = bcadd($subtotalFactura, $subtotalLinea, 2);
                $impuestoFactura = bcadd($impuestoFactura, $impuestoLinea, 2);
                $totalFactura = bcadd($totalFactura, $totalLinea, 2);
            }

            $factura = Factura::query()->create([
                'numero' => $this->generarNumeroFacturaAction->ejecutar(),
                'cliente_id' => $cliente->id,
                'user_id' => $usuario->id,
                'fecha' => $datos['fecha'] ?? now()->toDateString(),
                'subtotal' => $subtotalFactura,
                'impuesto' => $impuestoFactura,
                'total' => $totalFactura,
                'observaciones' => $datos['observaciones'] ?? null,
                'estado' => EstadoFacturaEnum::EMITIDA->value,
            ]);

            foreach ($detallesPreparados as $detalle) {
                $factura->detalles()->create([
                    'producto_id' => $detalle['producto']->id,
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'subtotal_linea' => $detalle['subtotal_linea'],
                    'impuesto_linea' => $detalle['impuesto_linea'],
                    'total_linea' => $detalle['total_linea'],
                ]);

                $detalle['producto']->update([
                    'stock' => $detalle['stock_despues'],
                ]);

                $this->registrarMovimientoInventarioAction->ejecutar(
                    producto: $detalle['producto'],
                    tipo: TipoMovimientoInventarioEnum::SALIDA->value,
                    cantidad: $detalle['cantidad'],
                    stockAntes: $detalle['stock_antes'],
                    stockDespues: $detalle['stock_despues'],
                    referenciaTipo: 'factura',
                    referenciaId: $factura->id,
                    observaciones: "Salida registrada por la factura {$factura->numero}."
                );
            }

            $factura->load([
                'cliente',
                'usuario.roles.permisos',
                'detalles.producto',
            ]);

            $this->auditoriaService->registrar(
                accion: 'crear',
                tabla: 'facturas',
                registroId: $factura->id,
                descripcion: 'Factura creada correctamente.',
                datosNuevos: [
                    'numero' => $factura->numero,
                    'cliente_id' => $factura->cliente_id,
                    'user_id' => $factura->user_id,
                    'subtotal' => $factura->subtotal,
                    'impuesto' => $factura->impuesto,
                    'total' => $factura->total,
                    'detalles' => $factura->detalles->map(fn ($detalle) => [
                        'producto_id' => $detalle->producto_id,
                        'cantidad' => $detalle->cantidad,
                        'precio_unitario' => $detalle->precio_unitario,
                        'total_linea' => $detalle->total_linea,
                    ])->all(),
                ],
                usuario: $usuario
            );

            return $factura;
        });
    }

    private function decimal(string|int|float|null $valor): string
    {
        return number_format((float) ($valor ?? 0), 2, '.', '');
    }

    private function calcularImpuesto(string $base, string $tasa): string
    {
        return bcdiv(bcmul($base, $tasa, 4), '100', 2);
    }
}
