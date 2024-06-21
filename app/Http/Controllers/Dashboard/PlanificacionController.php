<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ArtProv;
use App\Models\PlanDetalle;
use App\Models\Planificacion;
use App\Models\ReceDetalle;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PlanificacionController extends Controller
{

    public function index()
    {
        return view('dashboard.planificacion.index');
    }

    public function imprimirPlanificacion($id)
    {
        $planificacion = Planificacion::find($id);
        if (!$planificacion){
            return redirect()->route('planificacion.index');
        }

        $semana = getSemana($planificacion->fecha);

        $data = [
            'planificacion' => $planificacion,
            'semana' => $semana,
            'lunes' => $this->getDetalles($planificacion->id, $semana[1]),
            'martes' => $this->getDetalles($planificacion->id, $semana[2]),
            'miercoles' => $this->getDetalles($planificacion->id, $semana[3]),
            'jueves' => $this->getDetalles($planificacion->id, $semana[4]),
            'viernes' => $this->getDetalles($planificacion->id, $semana[5]),
            'sabado' => $this->getDetalles($planificacion->id, $semana[6]),
            'domingo' => $this->getDetalles($planificacion->id, $semana[7]),
        ];

        $pdf = Pdf::loadView('dashboard._export.pdf_planificacion', $data);
        return $pdf->stream("PLANIFICACION SEMANA $semana[0]-$semana[8].pdf");
    }

    public function imprimirRequerimientos($id)
    {
        $planificacion = Planificacion::find($id);
        if (!$planificacion){
            return redirect()->route('planificacion.index');
        }

        $semana = getSemana($planificacion->fecha);

        $listarArticulos = [];

        //DETALLES PLANIFICACION
        $detallesPlanificacion = PlanDetalle::where('planificaciones_id', $planificacion->id)->get();
        foreach ($detallesPlanificacion as $planficacion){
            //recetas
            $idReceta = $planficacion->recetas_id;
            $cantidadReceta = $planficacion->cantidad;
            //articulos
            $detallesReceta = ReceDetalle::where('recetas_id', $idReceta)->get();
            foreach ($detallesReceta as $articulo){
                $proveedor = null;
                $idArticulo = $articulo->articulos_id;
                $cantidadArticulo = $articulo->cantidad;

                $getProveedor = ArtProv::where('articulos_id', $idArticulo)->where('estatus', 1)->orderBy('created_at', 'ASC')->first();

                if ($getProveedor){
                    $proveedor = $getProveedor->proveedor->nombre;
                }

                $buscar = array_key_exists(mb_strtoupper($articulo->articulo->codigo), $listarArticulos);

                if (!$buscar){
                    $listarArticulos[''.mb_strtoupper($articulo->articulo->codigo).''] = [
                        'codigo' => mb_strtoupper($articulo->articulo->codigo),
                        'descripcion' => mb_strtoupper($articulo->articulo->descripcion),
                        'unidad' => mb_strtoupper($articulo->articulo->unidad->codigo),
                        'cantidad' => $cantidadReceta * $cantidadArticulo,
                        'proveedor' => mb_strtoupper($proveedor)
                    ];
                }else{
                    $cantidad = $listarArticulos[''.mb_strtoupper($articulo->articulo->codigo).'']['cantidad'];
                    $listarArticulos[''.mb_strtoupper($articulo->articulo->codigo).'']['cantidad'] = $cantidad + ($cantidadReceta * $cantidadArticulo);
                }

            }
        }

        $data = [
            'planificacion' => $planificacion,
            'semana' => $semana,
            'listarArticulos' => $listarArticulos,
        ];

        $pdf = Pdf::loadView('dashboard._export.pdf_requerimientos', $data);
        return $pdf->stream("REQUERIMIENTOS SEMANA $semana[0]-$semana[8].pdf");
    }

    protected function getDetalles($id, $fecha)
    {
        return PlanDetalle::where('planificaciones_id', $id)->where('fecha', $fecha)->get();
    }

}
