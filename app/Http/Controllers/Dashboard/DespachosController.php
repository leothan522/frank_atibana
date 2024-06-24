<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Despacho;
use App\Models\DespDetalle;
use Illuminate\Http\Request;

class DespachosController extends Controller
{
    public function index()
    {
        return view('dashboard.despachos.index');
    }
    public function printDespacho($id)
    {
        $despacho = Despacho::find($id);

        if (!$despacho){
            return redirect()->route('despachos.index');
        }

        $verSegmento = null;
        if ($despacho->segmentos_id){
            $verSegmento = $despacho->segmento->descripcion;
        }

        $listarDetalles = DespDetalle::where('despachos_id', $despacho->id)->get();

        return view('dashboard.despachos.print')
            ->with('despachos_id', $id)
            ->with('empresa', $despacho->empresa->nombre)
            ->with('codigo', $despacho->codigo)
            ->with('fecha', $despacho->fecha)
            ->with('descripcion', $despacho->descripcion)
            ->with('segmentos_id', $despacho->segmentos_id)
            ->with('verSegmento', $verSegmento)
            ->with('listarDetalles', $listarDetalles);
    }
}
