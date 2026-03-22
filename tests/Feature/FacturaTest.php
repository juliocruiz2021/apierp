<?php

namespace Tests\Feature;

use App\Enums\EstadoRegistroEnum;
use App\Models\Cliente;
use App\Models\MovimientoInventario;
use App\Models\Producto;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FacturaTest extends TestCase
{
    use RefreshDatabase;

    public function test_puede_crear_factura_y_descontar_inventario(): void
    {
        $this->seed(DatabaseSeeder::class);

        $usuario = User::query()->where('email', 'admin@apierp.local')->firstOrFail();
        Sanctum::actingAs($usuario);

        $cliente = Cliente::query()->create([
            'codigo' => 'CLI-001',
            'nombre' => 'Cliente de prueba',
            'estado' => EstadoRegistroEnum::ACTIVO->value,
        ]);

        $producto = Producto::query()->create([
            'codigo' => 'PRO-001',
            'nombre' => 'Producto de prueba',
            'precio' => 10.00,
            'stock' => 5.00,
            'stock_minimo' => 1.00,
            'estado' => EstadoRegistroEnum::ACTIVO->value,
        ]);

        $respuesta = $this->postJson('/api/facturas', [
            'cliente_id' => $cliente->id,
            'fecha' => '2026-03-22',
            'observaciones' => 'Factura de prueba',
            'detalle' => [
                [
                    'producto_id' => $producto->id,
                    'cantidad' => 2,
                    'precio_unitario' => 10.00,
                ],
            ],
        ]);

        $respuesta
            ->assertCreated()
            ->assertJson([
                'exito' => true,
                'mensaje' => 'Factura creada correctamente.',
            ]);

        $producto->refresh();

        $this->assertSame('3.00', $producto->stock);
        $this->assertDatabaseCount('movimientos_inventario', 1);
        $this->assertDatabaseHas('movimientos_inventario', [
            'producto_id' => $producto->id,
            'tipo' => 'salida',
        ]);

        $movimiento = MovimientoInventario::query()->firstOrFail();
        $this->assertSame('5.00', $movimiento->stock_antes);
        $this->assertSame('3.00', $movimiento->stock_despues);
    }
}
