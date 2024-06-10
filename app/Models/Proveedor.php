<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = "proveedores";
    protected $fillable = [
        'rif',
        'nombre',
        'direccion',
        'telefono',
        'banco',
        'cuenta',
        'imagen',
        'mini',
        'detail',
        'cart',
        'banner',
        'estatus'
    ];

    public function scopeBuscar($query, $keyword)
    {
        return $query->where('rif', 'LIKE', "%$keyword%")
            ->orWhere('nombre', 'LIKE', "%$keyword%");
    }

    public function articulos(): HasMany
    {
        return $this->hasMany(ArtProv::class, 'proveedores_id', 'id');
    }

}
