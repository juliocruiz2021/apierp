<?php

namespace Database\Seeders;

use App\Enums\EstadoRegistroEnum;
use App\Models\Permiso;
use Illuminate\Database\Seeder;

class PermisoSeeder extends Seeder
{
    public function run(): void
    {
        $permisos = [
            ['nombre' => 'Ver usuarios', 'clave' => 'usuarios.ver', 'descripcion' => 'Permite consultar usuarios.'],
            ['nombre' => 'Crear usuarios', 'clave' => 'usuarios.crear', 'descripcion' => 'Permite crear usuarios.'],
            ['nombre' => 'Actualizar usuarios', 'clave' => 'usuarios.actualizar', 'descripcion' => 'Permite actualizar usuarios.'],
            ['nombre' => 'Ver roles', 'clave' => 'roles.ver', 'descripcion' => 'Permite consultar roles.'],
            ['nombre' => 'Crear roles', 'clave' => 'roles.crear', 'descripcion' => 'Permite crear roles.'],
            ['nombre' => 'Actualizar roles', 'clave' => 'roles.actualizar', 'descripcion' => 'Permite actualizar roles.'],
            ['nombre' => 'Ver permisos', 'clave' => 'permisos.ver', 'descripcion' => 'Permite consultar permisos.'],
            ['nombre' => 'Crear permisos', 'clave' => 'permisos.crear', 'descripcion' => 'Permite crear permisos.'],
            ['nombre' => 'Actualizar permisos', 'clave' => 'permisos.actualizar', 'descripcion' => 'Permite actualizar permisos.'],
            ['nombre' => 'Ver clientes', 'clave' => 'clientes.ver', 'descripcion' => 'Permite consultar clientes.'],
            ['nombre' => 'Crear clientes', 'clave' => 'clientes.crear', 'descripcion' => 'Permite crear clientes.'],
            ['nombre' => 'Actualizar clientes', 'clave' => 'clientes.actualizar', 'descripcion' => 'Permite actualizar clientes.'],
            ['nombre' => 'Ver productos', 'clave' => 'productos.ver', 'descripcion' => 'Permite consultar productos.'],
            ['nombre' => 'Crear productos', 'clave' => 'productos.crear', 'descripcion' => 'Permite crear productos.'],
            ['nombre' => 'Actualizar productos', 'clave' => 'productos.actualizar', 'descripcion' => 'Permite actualizar productos.'],
            ['nombre' => 'Ver facturas', 'clave' => 'facturas.ver', 'descripcion' => 'Permite consultar facturas.'],
            ['nombre' => 'Crear facturas', 'clave' => 'facturas.crear', 'descripcion' => 'Permite crear facturas.'],
            ['nombre' => 'Ver movimientos de inventario', 'clave' => 'movimientos_inventario.ver', 'descripcion' => 'Permite consultar movimientos de inventario.'],
            ['nombre' => 'Ver auditoría', 'clave' => 'auditoria.ver', 'descripcion' => 'Permite consultar auditoría.'],
        ];

        foreach ($permisos as $permiso) {
            Permiso::query()->updateOrCreate(
                ['clave' => $permiso['clave']],
                [
                    'nombre' => $permiso['nombre'],
                    'descripcion' => $permiso['descripcion'],
                    'estado' => EstadoRegistroEnum::ACTIVO->value,
                ]
            );
        }
    }
}
