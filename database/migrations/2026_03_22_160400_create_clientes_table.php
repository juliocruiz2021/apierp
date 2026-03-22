<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->string('tipo_documento', 50)->nullable();
            $table->string('numero_documento', 50)->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('correo')->nullable();
            $table->text('direccion')->nullable();
            $table->string('estado', 20)->default('activo');
            $table->timestamps();

            $table->index('nombre');
            $table->index('numero_documento');
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
