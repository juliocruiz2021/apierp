<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->restrictOnDelete();
            $table->string('tipo', 20);
            $table->decimal('cantidad', 14, 2);
            $table->decimal('stock_antes', 14, 2);
            $table->decimal('stock_despues', 14, 2);
            $table->string('referencia_tipo', 100);
            $table->unsignedBigInteger('referencia_id');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index('producto_id');
            $table->index(['referencia_tipo', 'referencia_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
    }
};
