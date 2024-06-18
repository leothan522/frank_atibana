<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Planificacion extends Model
{
    use HasFactory;

    protected $table = "planificaciones";
    protected $fillable = [
        'empresas_id',
        'codigo',
        'descripcion',
        'fecha',
        'auditoria',
        'estatus'
    ];

    public function scopeBuscar($query, $keyword)
    {
        return $query->where('codigo', 'LIKE', "%$keyword%")
            ->orWhere('descripcion', 'LIKE', "%$keyword%")
            ;
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresas_id', 'id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(PlanDetalle::class, 'planificaciones_id', 'id');
    }

}
