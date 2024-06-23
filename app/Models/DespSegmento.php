<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DespSegmento extends Model
{
    use HasFactory;
    protected $table = "despachos_segmentos";
    protected $fillable = [
        'descripcion',
        'tipo'
    ];

    public function scopeBuscar($query, $keyword)
    {
        return $query->where('descripcion', 'LIKE', "%$keyword%")
            ;
    }

    public function despachos(): HasMany
    {
        return $this->hasMany(Despacho::class, 'segmentos_id', 'id');
    }

}
