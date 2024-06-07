<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AjusSegmento extends Model
{
    use HasFactory;
    protected $table = "ajustes_segmentos";
    protected $fillable = [
        'descripcion',
        'tipo'
    ];

    public function scopeBuscar($query, $keyword)
    {
        return $query->where('descripcion', 'LIKE', "%$keyword%")
            ;
    }

    public function ajustes(): HasMany
    {
        return $this->hasMany(Ajuste::class, 'segmentos_id', 'id');
    }

}
