<?php

namespace App\Livewire\Dashboard;

use App\Models\Articulo;
use App\Models\ArtUnid;
use App\Models\ReceDetalle;
use App\Models\Receta;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class RecetasComponent extends Component
{
    use LivewireAlert;

    public $rows = 0, $numero = 14, $empresas_id, $tableStyle = false;
    public $view, $nuevo = true, $cancelar = false, $footer = false, $edit = false, $new_receta = false, $keyword;
    public $recetas_id, $codigo, $fecha, $descripcion, $cantidad, $estatus;
    public $ajuste_contador = 1, $ajusteItem, $ajusteListarArticulos, $keywordAjustesArticulos, $listarDetalles;
    public $ajusteArticulo = [], $classArticulo = [], $ajusteDescripcion = [],
            $ajusteUnidad = [], $selectUnidad = [], $ajusteCantidad = [],
            $ajuste_articulos_id = [], $detalles_id = [], $borraritems = [];

    public function mount()
    {
        $this->setLimit();
    }

    public function render()
    {
        $recetas = Receta::buscar($this->keyword)
            ->where('empresas_id', $this->empresas_id)
            ->orderBy('codigo', 'ASC')
            ->limit($this->rows)
            ->get();
        $rowsRecetas = Receta::count();

        if ($rowsRecetas > $this->numero) {
            $this->tableStyle = true;
        }

        return view('livewire.dashboard.recetas-component')
            ->with('listarRecetas', $recetas)
            ->with('rowsRecetas', $rowsRecetas)
            ;
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

    #[On('getEmpresaRecetas')]
    public function getEmpresaRecetas($empresaID)
    {
        $this->empresas_id = $empresaID;
        $this->limpiar();
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
            'view', 'nuevo', 'cancelar', 'footer', 'new_receta',
            'codigo', 'fecha', 'descripcion', 'cantidad',
            'ajuste_contador', 'ajusteItem', 'ajusteListarArticulos', 'keywordAjustesArticulos', 'listarDetalles',
            'ajusteArticulo', 'classArticulo', 'ajusteDescripcion',
            'ajusteUnidad', 'selectUnidad', 'ajusteCantidad',
            'ajuste_articulos_id', 'detalles_id', 'borraritems'
        ]);
    }

    public function create()
    {
        $this->limpiar();
        $this->new_receta = true;
        $this->view = "form";
        $this->nuevo = false;
        $this->cancelar = true;
        $this->edit = false;
        $this->footer = false;
        $this->ajusteArticulo[0] = null;
        $this->classArticulo[0] = null;
        $this->ajusteDescripcion[0] = null;
        $this->selectUnidad[0] = array();
        $this->ajusteUnidad[0] = null;
        $this->ajusteCantidad[0] = null;
        $this->detalles_id[0] = null;
    }

    protected function rules()
    {
        return [
            'codigo' => ['required', 'min:4', 'alpha_dash:ascii', Rule::unique('recetas', 'codigo')->ignore($this->recetas_id)],
            'fecha' => 'nullable',
            'descripcion' => 'required|min:4',
            'cantidad' => 'nullable',
            'ajusteArticulo.*' => ['required', Rule::exists('articulos', 'codigo')],
            'ajusteUnidad.*' => 'required',
            'ajusteCantidad.*' => 'required'
        ];
    }

    public function save()
    {
        $this->validate();

        if (empty($this->fecha)) {
            $this->fecha = date("Y-m-d H:i");
        }
        //guardo la receta
        $receta = new Receta();
        $receta->empresas_id = $this->empresas_id;
        $receta->codigo = $this->codigo;
        $receta->descripcion = $this->descripcion;
        $receta->fecha = $this->fecha;
        $receta->cantidad = $this->cantidad;
        $receta->save();

        //guardo los detalles
        for ($i = 0; $i < $this->ajuste_contador; $i++) {
            $detalles = new ReceDetalle();
            $detalles->recetas_id = $receta->id;
            $detalles->articulos_id = $this->ajuste_articulos_id[$i];
            $detalles->unidades_id = $this->ajusteUnidad[$i];
            $detalles->cantidad = $this->ajusteCantidad[$i];
            $detalles->save();
        }
        $this->show($receta->id);
        $this->alert('success', 'Receta Guardada.');
    }

    #[On('show')]
    public function show($id)
    {
        $this->limpiar();
        $receta = Receta::find($id);
        $this->edit = true;
        $this->view = "show";
        $this->footer = true;
        $this->recetas_id = $receta->id;
        $this->codigo = $receta->codigo;
        $this->fecha = $receta->fecha;
        $this->descripcion = $receta->descripcion;
        $this->cantidad = $receta->cantidad;
        $this->estatus = $receta->estatus;

        $this->listarDetalles = ReceDetalle::where('recetas_id', $this->recetas_id)->get();
        $this->ajuste_contador = ReceDetalle::where('recetas_id', $this->recetas_id)->count();
    }

    public function update()
    {
        $this->validate();

        $procesar = false;

        $receta = Receta::find($this->recetas_id);
        $db_codigo = $receta->codigo;
        $db_fecha = $receta->fecha;
        $db_descripcion = $receta->descripcion;
        $db_cantidad = $receta->cantidad;

        if ($db_codigo != $this->codigo) {
            $procesar = true;
            $receta->codigo = $this->codigo;
        }

        if ($db_fecha != $this->fecha) {
            $procesar = true;
            $receta->fecha = $this->fecha;
        }

        if ($db_descripcion != $this->descripcion) {
            $procesar = true;
            $receta->descripcion = $this->descripcion;
        }

        if ($db_cantidad != $this->cantidad) {
            $procesar = true;
            $receta->cantidad = $this->cantidad;
        }

        //***** Detalles ******

        if (!empty($this->borraritems)) {
            $procesar = true;
        }

        for ($i = 0; $i < $this->ajuste_contador; $i++) {

            $detalle_id = $this->detalles_id[$i];
            $articulo_id = $this->ajuste_articulos_id[$i];
            $unidad_id = $this->ajusteUnidad[$i];
            $cantidad = $this->ajusteCantidad[$i];

            if ($detalle_id) {
                //seguimos validando
                $detalles = ReceDetalle::find($detalle_id);
                $db_articulo_id = $detalles->articulos_id;
                $db_unidad_id = $detalles->unidades_id;
                $db_cantidad = $detalles->cantidad;

                if ($db_articulo_id != $articulo_id) {
                    $procesar = true;
                }
                if ($db_unidad_id != $unidad_id) {
                    $procesar = true;
                }
                if ($db_cantidad != $cantidad) {
                    $procesar = true;
                }

            } else {
                //nuevo renglon
                $procesar = true;
            }

        }

        // fin detalles

        if ($procesar){

            $receta->save();

            //************** Detalles *********************

            //borramos los item viejos
            $itemViejos = ReceDetalle::where('recetas_id', $receta->id)->get();
            foreach ($itemViejos as $item){
                    $detalle = ReceDetalle::find($item->id);
                    $detalle->delete();
            }

            //guardamos los item nuevos
            for ($i = 0; $i < $this->ajuste_contador; $i++) {
                $detalles = new ReceDetalle();
                $detalles->recetas_id = $receta->id;
                $detalles->articulos_id = $this->ajuste_articulos_id[$i];
                $detalles->unidades_id = $this->ajusteUnidad[$i];
                $detalles->cantidad = $this->ajusteCantidad[$i];
                $detalles->save();
            }

            $this->show($receta->id);
            $this->alert('success', 'Receta Actualizada.');

        }else{
            $this->alert('info', 'No se realizo ningún cambio.');
            $this->show($this->recetas_id);
        }

    }

    public function destroy()
    {
        $this->confirm('¿Estas seguro?', [
            'toast' => false,
            'position' => 'center',
            'showConfirmButton' => true,
            'confirmButtonText' => '¡Sí, bórralo!',
            'text' => '¡No podrás revertir esto!',
            'cancelButtonText' => 'No',
            'onConfirmed' => 'confirmed',
        ]);
    }

    #[On('confirmed')]
    public function confirmed()
    {
        $receta = Receta::find($this->recetas_id);

        //codigo para verificar si realmente se puede borrar, dejar false si no se requiere validacion
        $vinculado = false;

        if ($vinculado) {
            $this->alert('warning', '¡No se puede Borrar!', [
                'position' => 'center',
                'timer' => '',
                'toast' => false,
                'text' => 'El registro que intenta borrar ya se encuentra vinculado con otros procesos.',
                'showConfirmButton' => true,
                'onConfirmed' => '',
                'confirmButtonText' => 'OK',
            ]);
        } else {
            $receta->delete();
            $this->alert('success', 'Receta Eliminada.');
            $this->edit = false;
            $this->limpiar();
        }
    }

    public function btnContador($opcion)
    {
        if ($opcion == "add") {
            $this->ajusteArticulo[$this->ajuste_contador] = null;
            $this->ajuste_articulos_id[$this->ajuste_contador] = null;
            $this->classArticulo[$this->ajuste_contador] = null;
            $this->ajusteDescripcion[$this->ajuste_contador] = null;
            $this->selectUnidad[$this->ajuste_contador] = array();
            $this->ajusteUnidad[$this->ajuste_contador] = null;
            $this->ajusteCantidad[$this->ajuste_contador] = null;
            $this->detalles_id[$this->ajuste_contador] = null;
            $this->ajuste_contador++;
        } else {

            if ($this->detalles_id[$opcion]) {
                $this->borraritems[] = [
                    'id' => $this->detalles_id[$opcion]
                ];
            }

            for ($i = $opcion; $i < $this->ajuste_contador - 1; $i++) {
                $this->ajusteArticulo[$i] = $this->ajusteArticulo[$i + 1];
                $this->ajuste_articulos_id[$i] = $this->ajuste_articulos_id[$i + 1];
                $this->classArticulo[$i] = $this->classArticulo[$i + 1];
                $this->ajusteDescripcion[$i] = $this->ajusteDescripcion[$i + 1];
                $this->selectUnidad[$i] = $this->selectUnidad[$i + 1];
                $this->ajusteUnidad[$i] = $this->ajusteUnidad[$i + 1];
                $this->ajusteCantidad[$i] = $this->ajusteCantidad[$i + 1];
                $this->detalles_id[$i] = $this->detalles_id[$i + 1];
            }
            $this->ajuste_contador--;
            unset($this->ajusteArticulo[$this->ajuste_contador]);
            unset($this->classArticulo[$this->ajuste_contador]);
            unset($this->ajusteDescripcion[$this->ajuste_contador]);
            unset($this->selectUnidad[$this->ajuste_contador]);
            unset($this->ajusteUnidad[$this->ajuste_contador]);
            unset($this->ajusteCantidad[$this->ajuste_contador]);
            unset($this->detalles_id[$this->ajuste_contador]);
        }
    }

    public function itemTemporalAjuste($int)
    {
        $this->ajusteItem = $int;
    }

    public function buscarAjustesArticulos()
    {
        $this->ajusteListarArticulos = Articulo::buscar($this->keywordAjustesArticulos)
            ->where('empresas_id', $this->empresas_id)
            ->where('estatus', 1)
            ->limit(100)
            ->get();
    }

    public function selectArticuloAjuste($codigo)
    {
        $this->ajusteArticulo[$this->ajusteItem] = $codigo;
        $this->updatedAjusteArticulo();
    }

    public function updatedAjusteArticulo()
    {
        foreach ($this->ajusteArticulo as $key => $value) {
            $array = array();
            if ($value) {
                $articulo = Articulo::where('codigo', $value)
                    ->where('empresas_id', $this->empresas_id)
                    ->where('estatus', 1)
                    ->first();
                if ($articulo && !empty($articulo->unidades_id)) {
                    $array[] = [
                        'id' => $articulo->unidades_id,
                        'codigo' => $articulo->unidad->codigo
                    ];
                    $unidades = ArtUnid::where('articulos_id', $articulo->id)->get();
                    foreach ($unidades as $unidad) {
                        $array[] = [
                            'id' => $unidad->unidades_id,
                            'codigo' => $unidad->unidad->codigo
                        ];
                    }
                    $this->ajusteDescripcion[$key] = $articulo->descripcion;
                    $this->selectUnidad[$key] = $array;
                    if (is_null($this->ajusteUnidad[$key])) {
                        $this->ajusteUnidad[$key] = $articulo->unidades_id;
                    }
                    $this->resetErrorBag('ajusteArticulo.' . $key);
                    $this->resetErrorBag('ajusteUnidad.' . $key);
                    $this->ajuste_articulos_id[$key] = $articulo->id;
                    $this->classArticulo[$key] = "is-valid";
                } else {
                    $this->classArticulo[$key] = "is-invalid";
                    $this->ajusteDescripcion[$key] = null;
                    $this->ajuste_articulos_id[$key] = null;
                    $this->selectUnidad[$key] = array();
                    $this->ajusteUnidad[$key] = null;
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
        if ($this->recetas_id) {
            $this->show($this->recetas_id);
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

        $i = 0;
        foreach ($this->listarDetalles as $detalle) {
            $array = array();
            $array[] = [
                'id' => $detalle->articulo->unidades_id,
                'codigo' => $detalle->articulo->unidad->codigo
            ];
            $unidades = ArtUnid::where('articulos_id', $detalle->articulos_id)->get();
            foreach ($unidades as $unidad) {
                $array[] = [
                    'id' => $unidad->unidades_id,
                    'codigo' => $unidad->unidad->codigo
                ];
            }
            $this->ajusteArticulo[$i] = $detalle->articulo->codigo;
            $this->ajuste_articulos_id[$i] = $detalle->articulos_id;
            $this->classArticulo[$i] = null;
            $this->ajusteDescripcion[$i] = $detalle->articulo->descripcion;
            $this->selectUnidad[$i] = $array;
            $this->ajusteUnidad[$i] = $detalle->unidades_id;
            $this->ajusteCantidad[$i] = $detalle->cantidad;
            $this->detalles_id[$i] = $detalle->id;
            $i++;
        }
    }

    public function btnActivoInactivo()
    {
        $receta = Receta::find($this->recetas_id);
        if ($this->estatus){
            $receta->estatus = 0;
            $this->estatus = 0;
            $message = "Receta Inactiva";
        }else{
            $receta->estatus = 1;
            $this->estatus = 1;
            $message = "Receta Activa";
        }
        $receta->update();
        $this->alert('success', $message);
    }

}
