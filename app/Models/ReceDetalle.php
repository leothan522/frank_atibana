<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceDetalle extends Model
{
    use HasFactory;

    protected $table = "recetas_detalles";
    protected $fillable = [
        'recetas_id',
        'articulos_id',
        'unidades_id',
        'cantidad',
        'renglon'
    ];

    public function receta(): BelongsTo
    {
        return $this->belongsTo(Receta::class, 'recetas_id', 'id');
    }

    public function articulo(): BelongsTo
    {
        return $this->belongsTo(Articulo::class, 'articulos_id', 'id');
    }

    public function unidad(): BelongsTo
    {
        return $this->belongsTo(Unidad::class, 'unidades_id', 'id');
    }

}
