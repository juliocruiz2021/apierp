<?php

namespace Database\Seeders;

use App\Enums\EstadoRegistroEnum;
use App\Models\Permiso;
use App\Models\Rol;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        $rolAdministrador = Rol::query()->updateOrCreate(
            ['nombre' => 'Administrador'],
            [
                'descripcion' => 'Rol con acceso total al ERP.',
                'estado' => EstadoRegistroEnum::ACTIVO->value,
            ]
        );

        $rolAdministrador->permisos()->sync(
            Permiso::query()->pluck('id')->all()
        );
    }
}
