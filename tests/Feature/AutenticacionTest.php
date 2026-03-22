<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutenticacionTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_usuario_administrador_puede_iniciar_sesion(): void
    {
        $this->seed(DatabaseSeeder::class);

        $respuesta = $this->postJson('/api/login', [
            'email' => 'admin@apierp.local',
            'password' => 'Admin123!',
        ]);

        $respuesta
            ->assertOk()
            ->assertJson([
                'exito' => true,
                'mensaje' => 'Inicio de sesión realizado correctamente.',
            ])
            ->assertJsonStructure([
                'exito',
                'mensaje',
                'datos' => [
                    'token',
                    'tipo_token',
                    'usuario' => [
                        'id',
                        'nombre',
                        'email',
                        'estado',
                    ],
                ],
            ]);
    }
}
