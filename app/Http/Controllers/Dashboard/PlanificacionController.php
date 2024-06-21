<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PlanDetalle;
use App\Models\Planificacion;
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
        return $pdf->stream('prueba.pdf');
    }

    public function imprimirRequerimientos($id)
    {
        $pdf = Pdf::loadView('dashboard._export.pdf_planificacion');
        return $pdf->stream('prueba.pdf');
    }

    protected function getDetalles($id, $fecha)
    {
        return PlanDetalle::where('planificaciones_id', $id)->where('fecha', $fecha)->get();
    }

}
