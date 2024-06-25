<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\MovimientosExport;
use App\Http\Controllers\Controller;
use App\Models\AjusDetalle;
use App\Models\Ajuste;
use App\Models\AjusTipo;
use App\Models\Almacen;
use App\Models\Despacho;
use App\Models\DespDetalle;
use App\Models\Empresa;
use App\Models\Parametro;
use App\Models\ReceDetalle;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CompartirController extends Controller
{
    public function index($token)
    {
        $parametro = Parametro::where('nombre', 'compartir_stock_qr')->first();
        if ($parametro){
            if ($parametro->valor == $token){
                return view('dashboard.compartir.index')
                    ->with('empresa_id', $parametro->tabla_id);
            }
        }
        return redirect()->route('cerrar');
    }

    public function reporteMovimientos($almacenes_id, $empresas_id, $limit)
    {
        $empresa = Empresa::find($empresas_id);
        $almacen = Almacen::find($almacenes_id);

        $ajustes = Ajuste::where('empresas_id', $empresas_id)
            ->where('estatus', 1)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get();
        $i = 0;
        $listarMovimientos = [];
        foreach ($ajustes as $ajuste){

            $ajustes_id = $ajuste->id;
            $code = $ajuste->codigo;
            $fecha = Carbon::parse($ajuste->created_at)->format('Y-m-d H:i:s');;
            $segmento = null;
            if ($ajuste->segmentos_id){
                $segmento = $ajuste->segmentos->descripcion;
            }

            $detalles = AjusDetalle::where('ajustes_id', $ajuste->id)->where('almacenes_id', $almacenes_id)->get();
            $y = 0;
            $listarDetalles = [];
            foreach ($detalles as $detalle){
                $tipo = $detalle->tipo->codigo;
                $articulos_id = $detalle->articulos_id;
                $codigo = $detalle->articulo->codigo;
                $articulo = $detalle->articulo->descripcion;
                $unidades_id = $detalle->unidades_id;
                $unidad = $detalle->unidad->codigo;
                $cantidad = $detalle->cantidad;
                if ($detalle->tipo->tipo == 1){
                    $entrada = true;
                }else{
                    $entrada = false;
                }
                $listarDetalles[$y] = [
                    'tipo' => $tipo,
                    'codigo' => $codigo,
                    'articulo' => $articulo,
                    'unidad' => $unidad,
                    'cantidad' => $cantidad,
                    'entrada' => $entrada,
                    'articulos_id' => $articulos_id,
                    'almacenes_id' => $almacenes_id,
                    'unidades_id' => $unidades_id
                ];
                $y++;
            }

            $listarMovimientos[$i] = [
                'tabla' => 'ajustes',
                'id' => $ajustes_id,
                'codigo' => $code,
                'fecha' => $fecha,
                'segmento' => $segmento,
                'detalles' => $listarDetalles
            ];
            $i++;
        }

        $arrayAjustes = $listarMovimientos;

        $despachos = Despacho::where('empresas_id', $empresas_id)
            ->where('estatus', 1)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get();

        $i = 0;
        $listarMovimientos = [];
        foreach ($despachos as $despacho){

            $despachos_id = $despacho->id;
            $code = $despacho->codigo;
            $fecha = Carbon::parse($despacho->created_at)->format('Y-m-d H:i:s');
            $segmento = null;
            if ($despacho->segmentos_id){
                $segmento = $despacho->segmentos->descripcion;
            }

            $detalles = DespDetalle::where('despachos_id', $despacho->id)->where('almacenes_id', $almacenes_id)->get();

            foreach ($detalles as $detalle){
                $getTipo = AjusTipo::where('tipo', 2)->first();
                $tipo = $getTipo->codigo;

                $recetas = ReceDetalle::where('recetas_id', $detalle->recetas_id)->get();
                $y = 0;
                $listarDetalles = [];
                foreach ($recetas as $receta){
                    $articulos_id = $receta->articulos_id;
                    $codigo = $receta->articulo->codigo;
                    $articulo = $receta->articulo->descripcion;
                    $unidades_id = $receta->unidades_id;
                    $unidad = $receta->unidad->codigo;
                    $cantidad = $detalle->cantidad * $receta->cantidad;
                    $listarDetalles[$y] = [
                        'tipo' => $tipo,
                        'codigo' => $codigo,
                        'articulo' => $articulo,
                        'unidad' => $unidad,
                        'cantidad' => $cantidad,
                        'entrada' => false,
                        'articulos_id' => $articulos_id,
                        'almacenes_id' => $almacenes_id,
                        'unidades_id' => $unidades_id
                    ];
                    $y++;
                }
            }

            $listarMovimientos[$i] = [
                'tabla' => 'despachos',
                'id' => $despachos_id,
                'codigo' => $code,
                'fecha' => $fecha,
                'segmento' => $segmento,
                'detalles' => $listarDetalles
            ];
            $i++;
        }

        $arrayDespachos = $listarMovimientos;

        $arrayCombinados = array_merge($arrayAjustes, $arrayDespachos);

        $items = collect($arrayCombinados)->sortByDesc('fecha');

        $hoy = date('d-m-Y h:i:s a');
        return Excel::download(new MovimientosExport($empresa, $almacenes_id, $almacen, $items), 'Movimientos '.$hoy.'.xlsx');
    }
}
