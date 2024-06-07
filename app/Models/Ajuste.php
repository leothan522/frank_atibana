<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ajuste extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "ajustes";
    protected $fillable = [
      'empresas_id',
      'codigo',
      'descripcion',
      'fecha',
      'segmentos_id',
      'auditoria',
      'estatus'
    ];

    public function scopeBuscar($query, $keyword)
    {
        return $query->where('codigo', 'LIKE', "%$keyword%")
            ->orWhere('descripcion', 'LIKE', "%$keyword%")
            ;
    }

    public function empresas(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresas_id', 'id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(AjusDetalle::class, 'ajustes_id', 'id');
    }

    public function segmentos(): BelongsTo
    {
        return $this->belongsTo(AjusSegmento::class, 'segmentos_id', 'id');
    }

}
