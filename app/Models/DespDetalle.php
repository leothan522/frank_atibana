<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DespDetalle extends Model
{
    use HasFactory;
    protected $table = "despachos_detalles";
    protected $fillable = [
        'despachos_id',
        'recetas_id',
        'almacenes_id',
        'cantidad',
        'renglon'
    ];

    public function despacho(): BelongsTo
    {
        return $this->belongsTo(Despacho::class, 'despachos_id', 'id');
    }

    public function receta(): BelongsTo
    {
        return $this->belongsTo(Receta::class, 'recetas_id', 'id');
    }

    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class, 'almacenes_id', 'id');
    }

}
