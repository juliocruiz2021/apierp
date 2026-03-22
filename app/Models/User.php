<?php

namespace App\Models;

use App\Enums\EstadoRegistroEnum;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'estado',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Rol::class, 'rol_usuario', 'user_id', 'rol_id');
    }

    public function facturas(): HasMany
    {
        return $this->hasMany(Factura::class, 'user_id');
    }

    public function auditorias(): HasMany
    {
        return $this->hasMany(Auditoria::class, 'user_id');
    }

    public function permisos(): Collection
    {
        $this->loadMissing('roles.permisos');

        return $this->roles
            ->flatMap(fn (Rol $rol) => $rol->permisos)
            ->unique('id')
            ->values();
    }

    public function esAdministrador(): bool
    {
        $this->loadMissing('roles');

        return $this->roles->contains(
            fn (Rol $rol) => $rol->estado === EstadoRegistroEnum::ACTIVO->value
                && strcasecmp($rol->nombre, 'Administrador') === 0
        );
    }

    public function tienePermiso(string $clave): bool
    {
        if ($this->esAdministrador()) {
            return true;
        }

        return $this->permisos()->contains(
            fn (Permiso $permiso) => $permiso->estado === EstadoRegistroEnum::ACTIVO->value
                && $permiso->clave === $clave
        );
    }
}
