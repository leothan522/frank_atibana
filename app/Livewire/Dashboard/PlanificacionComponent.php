<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class PlanificacionComponent extends Component
{
    use LivewireAlert;

    public $rows = 0, $numero = 14, $empresas_id, $tableStyle = false;
    public $view, $nuevo = true, $cancelar = false, $footer = false, $edit = false, $new_planificacion = false, $keyword;
    public $planificaciones_id, $fecha, $descripcion, $estatus;

    public function mount()
    {
        $this->setLimit();
    }

    public function render()
    {
        return view('livewire.dashboard.planificacion-component');
    }

    public function setLimit()
    {
        if (numRowsPaginate() < $this->numero) {
            $rows = $this->numero;
        } else {
            $rows = numRowsPaginate();
        }
        $this->rows = $this->rows + $rows;
    }

    #[On('getEmpresaPlanificacion')]
    public function getEmpresaPlanificacion($empresaID)
    {
        $this->empresas_id = $empresaID;
        //$this->limpiar();
        /*$ultimo = Articulo::orderBy('codigo', 'ASC')->where('empresas_id', $this->empresas_id)->first();
        if ($ultimo) {
            $this->view = "show";
            $this->showArticulos($ultimo->id);
        }*/
    }

    public function limpiar()
    {
        $this->resetErrorBag();
        $this->reset([
            'view', 'nuevo', 'cancelar', 'footer', 'new_planificacion',
            /*'codigo', 'fecha', 'descripcion', 'cantidad',
            'ajuste_contador', 'ajusteItem', 'ajusteListarArticulos', 'keywordAjustesArticulos', 'listarDetalles',
            'ajusteArticulo', 'classArticulo', 'ajusteDescripcion',
            'ajusteUnidad', 'selectUnidad', 'ajusteCantidad',
            'ajuste_articulos_id', 'detalles_id', 'borraritems'*/
        ]);
    }

    public function create()
    {
        $this->limpiar();
        $this->new_planificacion = true;
        $this->view = "form";
        $this->nuevo = false;
        $this->cancelar = true;
        $this->edit = false;
        $this->footer = false;
        /*$this->ajusteArticulo[0] = null;
        $this->classArticulo[0] = null;
        $this->ajusteDescripcion[0] = null;
        $this->selectUnidad[0] = array();
        $this->ajusteUnidad[0] = null;
        $this->ajusteCantidad[0] = null;
        $this->detalles_id[0] = null;*/
    }

    public function save()
    {
        $date = Carbon::parse($this->fecha);
        ///dd($date->weekOfYear);
        //dd($date->dayOfWeek);
        //dd($date->startOfWeek()->format('Y-m-d'));
        //dd($date->endOfWeek()->format('Y-m-d'));
        dd($this->fecha);
    }

    #[On('buscar')]
    public function buscar($keyword)
    {
        $this->keyword = $keyword;
    }

    public function cerrarBusqueda()
    {
        $this->reset('keyword');
        $this->limpiar();
    }

    public function btnCancelar()
    {
        if ($this->planificaciones_id) {
            //$this->show($this->recetas_id);
        } else {
            $this->limpiar();
        }
    }

}
