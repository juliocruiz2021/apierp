<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->date('fecha')->index();
            $table->decimal('subtotal', 14, 2);
            $table->decimal('impuesto', 14, 2);
            $table->decimal('total', 14, 2);
            $table->text('observaciones')->nullable();
            $table->string('estado', 20)->default('emitida')->index();
            $table->timestamps();

            $table->index('cliente_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
