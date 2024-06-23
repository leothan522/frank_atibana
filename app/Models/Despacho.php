<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Despacho extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "despachos";
    protected $fillable = [
        'empresas_id',
        'codigo',
        'descripcion',
        'fecha',
        'segmentos_id',
        'auditoria',
        'estatus',
        'impreso',
    ];

    public function scopeBuscar($query, $keyword)
    {
        return $query->where('codigo', 'LIKE', "%$keyword%")
            ->orWhere('descripcion', 'LIKE', "%$keyword%")
            ;
    }

    public function segmento(): BelongsTo
    {
        return $this->belongsTo(DespSegmento::class, 'segmentos_id', 'id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DespDetalle::class, 'despachos_id', 'id');
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresas_id', 'id');
    }

}
