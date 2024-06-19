<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanDetalle extends Model
{
    use HasFactory;
    protected $table = "planificaciones_detalles";
    protected $fillable = [
        'planificaciones_id',
        'fecha',
        'recetas_id',
        'cantidad',
        'renglon'
    ];

    public function planificacion(): BelongsTo
    {
        return $this->belongsTo(Planificacion::class, 'planificaciones_id', 'id');
    }

    public function receta(): BelongsTo
    {
        return $this->belongsTo(Receta::class, 'recetas_id', 'id');
    }


}
