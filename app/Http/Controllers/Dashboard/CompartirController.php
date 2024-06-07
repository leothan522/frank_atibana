<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\MovimientosExport;
use App\Http\Controllers\Controller;
use App\Models\AjusDetalle;
use App\Models\Ajuste;
use App\Models\Almacen;
use App\Models\Empresa;
use App\Models\Parametro;
use App\Models\Stock;
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

    public $getAlmacen, $empresa_id, $getSaldo;
    public function reporteMovimientos($almacen_id, $empresa_id, $limit)
    {
        $this->getAlmacen = $almacen_id;
        $empresa = Empresa::find($empresa_id);
        $this->empresa_id = $empresa_id;
        $almacen = Almacen::find($this->getAlmacen);
        $getNombre = $almacen->nombre;
        $getAjustes = Ajuste::where('empresas_id', $this->empresa_id)->orderBy('fecha', 'DESC')->get();
        $getAjustes->each( function ($ajuste){
            $ajuste->detalles = AjusDetalle::where('ajustes_id', $ajuste->id)
                ->where('almacenes_id', $this->getAlmacen)->get();
            $ajuste->detalles->each(function ($detalle){
                $stock = Stock::where('empresas_id', $this->empresa_id)
                    ->where('articulos_id', $detalle->articulos_id)
                    ->where('almacenes_id', $detalle->almacenes_id)
                    ->where('unidades_id', $detalle->unidades_id)
                    ->first();
                if ($stock){
                    $this->getSaldo = $stock->actual;
                }else{
                    $this->getSaldo = 0;
                }
            });
        });

        /*return view('dashboard.compartir.excel_movimientos')
            ->with('getAjustes', $getAjustes)
            ->with('getSaldo', $this->getSaldo)
            ->with('getLimit', $limit)
            ->with('getNombre', $getNombre)
            ->with('empresa', $empresa)
            ;*/
        $hoy = date('d-m-Y h:i:s a');
        return Excel::download(new MovimientosExport($getAjustes, $this->getSaldo, $limit, $getNombre, $empresa), 'Movimientos '.$hoy.'.xlsx');
    }
}
