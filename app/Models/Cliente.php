<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'codigo',
        'nombre',
        'tipo_documento',
        'numero_documento',
        'telefono',
        'correo',
        'direccion',
        'estado',
    ];

    public function facturas(): HasMany
    {
        return $this->hasMany(Factura::class, 'cliente_id');
    }
}
