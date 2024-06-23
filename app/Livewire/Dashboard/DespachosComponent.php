<?php

namespace App\Livewire\Dashboard;

use App\Models\Receta;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class DespachosComponent extends Component
{

    use LivewireAlert;

    public $rows = 0, $numero = 14, $empresas_id, $tableStyle = false;
    public $view, $nuevo = true, $cancelar = false, $footer = false, $edit = false, $new_despacho = false, $keyword;

    public $contador = 1, $idReceta = [], $codigoReceta = [], $classReceta = [],
        $descripcionReceta = [], $cantidad = [], $detalles_id = [];

    public $borraritems = [], $item, $listarRecetas, $keywordRecetas;

    public $despachos_id, $codigo, $fecha, $descripcion, $estatus;

    public function render()
    {
        return view('livewire.dashboard.despachos-component');
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

    #[On('getEmpresaDespachos')]
    public function getEmpresaDespachos($empresaID)
    {
        $this->empresas_id = $empresaID;
        $this->limpiar();
        /*$ultimo = Receta::orderBy('created_at', 'DESC')->where('empresas_id', $this->empresas_id)->first();
        if ($ultimo) {
            $this->view = "show";
            $this->show($ultimo->id);
        }*/
    }

    public function limpiar()
    {
        $this->resetErrorBag();
        $this->reset([
            'view', 'nuevo', 'cancelar', 'footer', 'new_despacho',

            'contador', 'idReceta', 'codigoReceta', 'classReceta', 'descripcionReceta',
            'cantidad', 'detalles_id',

            'borraritems', 'item', 'listarRecetas', 'keywordRecetas',

            'codigo', 'fecha', 'descripcion',
        ]);
    }

    public function create()
    {
        $this->limpiar();
        $this->new_despacho = true;
        $this->view = "form";
        $this->nuevo = false;
        $this->cancelar = true;
        $this->edit = false;
        $this->footer = false;

        if ($this->contador){
            $this->idReceta[0] = null;
            $this->codigoReceta[0] = null;
            $this->classReceta[0] = null;
            $this->descripcionReceta[0] = null;
            $this->cantidad[0] = null;
            $this->detalles_id[0] = null;
        }
    }

    protected function rules()
    {
        return [
            'codigo' => ['nullable', Rule::unique('despachos', 'codigo')->ignore($this->despachos_id)],
            'fecha' => ['nullable'],
            'codigoReceta.*' => [Rule::requiredIf($this->contador > 0), Rule::exists('recetas', 'codigo')],
            'cantidad.*' => [Rule::requiredIf($this->contador > 0)],
        ];
    }

    public function btnContador($opcion)
    {
        if ($opcion == "add") {
            $this->idReceta[$this->contador] = null;
            $this->codigoReceta[$this->contador] = null;
            $this->classReceta[$this->contador] = null;
            $this->descripcionReceta[$this->contador] = null;
            $this->cantidad[$this->contador] = null;
            $this->detalles_id[$this->contador] = null;
            $this->contador++;
        } else {

            if ($this->detalles_id[$opcion]) {
                $this->borraritems[] = [
                    'id' => $this->detalles_id[$opcion]
                ];
            }

            for ($i = $opcion; $i < $this->contador - 1; $i++) {
                $this->idReceta[$i] = $this->idReceta[$i + 1];
                $this->codigoReceta[$i] = $this->codigoReceta[$i + 1];
                $this->classReceta[$i] = $this->classReceta[$i + 1];
                $this->descripcionReceta[$i] = $this->descripcionReceta[$i + 1];
                $this->cantidad[$i] = $this->cantidad[$i + 1];
                $this->detalles_id[$i] = $this->detalles_id[$i + 1];
            }
            $this->contador--;
            unset($this->idReceta[$this->contador]);
            unset($this->codigoReceta[$this->contador]);
            unset($this->classReceta[$this->contador]);
            unset($this->descripcionReceta[$this->contador]);
            unset($this->cantidad[$this->contador]);
            unset($this->detalles_id[$this->contador]);
        }
    }

    public function itemTemporal($int)
    {
        $this->item = $int;
    }

    public function buscarRecetas()
    {
        $this->listarRecetas = Receta::buscar($this->keywordRecetas)
            ->where('empresas_id', $this->empresas_id)
            ->where('estatus', 1)
            ->limit(100)
            ->get();
    }

    public function selectReceta($codigo)
    {
        $this->codigoReceta[$this->item] = $codigo;
        $this->updatedCodigoReceta();
    }

    public function updatedCodigoReceta()
    {
        foreach ($this->codigoReceta as $key => $value) {
            $array = array();
            if ($value) {
                $receta = Receta::where('codigo', $value)
                    ->where('empresas_id', $this->empresas_id)
                    ->where('estatus', 1)
                    ->first();
                if ($receta) {
                    $this->classReceta[$key] = "is-valid";
                    $this->descripcionReceta[$key] = mb_strtoupper($receta->descripcion);
                    $this->idReceta[$key] = $receta->id;
                    $this->resetErrorBag('codigoReceta.' . $key);
                } else {
                    $this->classReceta[$key] = "is-invalid";
                    $this->descripcionReceta[$key] = null;
                    $this->idReceta[$key] = null;
                }
            }
        }
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
        if ($this->despachos_id) {
            //$this->show($this->planificaciones_id);
        } else {
            $this->limpiar();
        }
    }

    public function btnEditar()
    {
        $this->view = 'form';
        $this->edit = false;
        $this->cancelar = true;
        $this->footer = false;
    }

}
