<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ReceDetalle;
use App\Models\Receta;
use Illuminate\Http\Request;

class RecetasController extends Controller
{

    public function index()
    {
        return view('dashboard.recetas.index');
    }

    public function printReceta($id)
    {
        $receta = Receta::find($id);
        if (!$receta){
            return redirect()->route('recetas.index');
        }
        $listarDetalles = ReceDetalle::where('recetas_id', $receta->id)->get();

        return view('dashboard.recetas.print')
            ->with('recetas_id', $id)
            ->with('empresa', $receta->empresa->nombre)
            ->with('codigo', $receta->codigo)
            ->with('fecha', $receta->fecha)
            ->with('descripcion', $receta->descripcion)
            ->with('cantidad', $receta->cantidad)
            ->with('listarDetalles', $listarDetalles);
    }

}
