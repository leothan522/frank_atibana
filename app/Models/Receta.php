<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receta extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "recetas";
    protected $fillable = [
        'empresas_id',
        'codigo',
        'descripcion',
        'fecha',
        'cantidad',
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
        return $this->hasMany(ReceDetalle::class, 'recetas_id', 'id');
    }

}
