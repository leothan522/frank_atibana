<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArtProv extends Model
{
    use HasFactory;

    protected $table = "articulos_proveedores";
    protected $fillable = [
        'articulos_id',
        'proveedores_id',
        'estatus'
    ];

    public function articulo(): BelongsTo
    {
        return $this->belongsTo(Articulo::class, 'articulos_id', 'id');
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class, 'proveedores_id', 'id');
    }

}
