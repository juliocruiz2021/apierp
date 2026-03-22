<?php

namespace Database\Seeders;

use App\Enums\EstadoRegistroEnum;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsuarioAdministradorSeeder extends Seeder
{
    public function run(): void
    {
        $usuario = User::query()->updateOrCreate(
            ['email' => 'admin@apierp.local'],
            [
                'name' => 'Administrador General',
                'password' => 'Admin123!',
                'estado' => EstadoRegistroEnum::ACTIVO->value,
            ]
        );

        $rolAdministrador = Rol::query()
            ->where('nombre', 'Administrador')
            ->firstOrFail();

        $usuario->roles()->sync([$rolAdministrador->id]);
    }
}
