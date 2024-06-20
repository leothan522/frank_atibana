<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
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
        $pdf = Pdf::loadView('dashboard._export.pdf_planificacion');
        return $pdf->stream('prueba.pdf');
    }

    public function imprimirRequerimientos($id)
    {
        $pdf = Pdf::loadView('dashboard._export.pdf_planificacion');
        return $pdf->stream('prueba.pdf');
    }

}
