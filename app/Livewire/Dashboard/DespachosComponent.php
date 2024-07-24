<?php

namespace App\Livewire\Dashboard;

use App\Models\Almacen;
use App\Models\Despacho;
use App\Models\DespDetalle;
use App\Models\DespSegmento;
use App\Models\Parametro;
use App\Models\ReceDetalle;
use App\Models\Receta;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class DespachosComponent extends Component
{

    use LivewireAlert;

    public $rows = 0, $numero = 14, $empresas_id, $tableStyle = false;
    public $view, $nuevo = true, $cancelar = false, $footer = false, $edit = false, $new_despacho = false, $keyword;

    public $contador = 1, $idReceta = [], $codigoReceta = [], $classReceta = [], $descripcionReceta = [],
        $idAlmacen = [], $codigoAlmacen = [], $classAlmacen = [], $cantidad = [], $detalles_id = [];

    public $borraritems = [], $item, $listarRecetas, $keywordRecetas;

    public $despachos_id, $codigo, $fecha, $descripcion, $segmentos_id, $estatus, $impreso;
    public $proximo_codigo;
    public $listarDetalles, $verSegmento;
    public $listarArticulos = [];
    public $opcionDestroy;

    public function mount()
    {
        $this->setLimit();
    }

    public function render()
    {
        $despachos = Despacho::buscar($this->keyword)
            ->where('empresas_id', $this->empresas_id)
            ->orderBy('fecha', 'DESC')
            ->limit($this->rows)
            ->get();
        $rowsDespachos = Despacho::count();

        if ($rowsDespachos > $this->numero) {
            $this->tableStyle = true;
        }

        $selectSegmentos = DespSegmento::orderBy('id', 'ASC')->get();

        return view('livewire.dashboard.despachos-component')
            ->with('selectSegmentos', $selectSegmentos)
            ->with('listarDespachos', $despachos)
            ->with('rowsDespachos', $rowsDespachos);
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
            'idAlmacen', 'codigoAlmacen', 'classAlmacen', 'cantidad', 'detalles_id',

            'borraritems', 'item', 'listarRecetas', 'keywordRecetas',

            'codigo', 'fecha', 'descripcion', 'segmentos_id', 'estatus', 'impreso',
            'proximo_codigo',
            'listarDetalles', 'verSegmento',
            'listarArticulos',
            'opcionDestroy'
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
        $this->proximo_codigo = $this->getNextCodigo();

        if ($this->contador){
            $this->idReceta[0] = null;
            $this->codigoReceta[0] = null;
            $this->classReceta[0] = null;
            $this->descripcionReceta[0] = null;
            $this->idAlmacen[0] = null;
            $this->codigoAlmacen[0] = null;
            $this->classAlmacen[0] = null;
            $this->cantidad[0] = null;
            $this->detalles_id[0] = null;
        }
    }

    protected function rules()
    {
        return [
            'codigo' => ['nullable', 'min:4', 'alpha_dash:ascii', Rule::unique('despachos', 'codigo')->ignore($this->despachos_id)],
            'fecha' => ['nullable'],
            'codigoReceta.*' => [Rule::requiredIf($this->contador > 0), Rule::exists('recetas', 'codigo')],
            'codigoAlmacen.*' => [Rule::requiredIf($this->contador > 0), Rule::exists('almacenes', 'codigo')],
            'cantidad.*' => [Rule::requiredIf($this->contador > 0)],
        ];
    }

    public function save()
    {
        $this->validate();

        if (empty($this->codigo)) {
            $this->codigo = $this->proximo_codigo['formato'] . cerosIzquierda($this->proximo_codigo['proximo'], numSizeCodigo());
        }

        if (empty($this->fecha)) {
            $this->fecha = date("Y-m-d H:i");
        }

        $procesar = true;
        $html = null;

        //para validar stock
        $listarArticulos = $this->listarArticulos();

        //Validamos el Stock
        foreach ($listarArticulos as $articulo){
            $stock = Stock::where('empresas_id', $this->empresas_id)
                ->where('articulos_id', $articulo['articulos_id'])
                ->where('almacenes_id', $articulo['almacenes_id'])
                ->where('unidades_id', $articulo['unidades_id'])
                ->first();
            if ($stock) {
                $disponible = $stock->disponible;
                if ($articulo['cantidad'] > $disponible) {
                    $procesar = false;
                    $html .= '<span class="text-sm">Para <strong>' . formatoMillares($articulo['cantidad'], 3) . ' '.$articulo['unidad'].' </strong> del articulo <strong>' . $articulo['codigo'] . '</strong>. El stock es <strong>' . formatoMillares($disponible, 3) . '</strong></span><br>';
                    $this->addError('cantidad.' . $articulo['i'], 'error');
                }
            } else {
                $procesar = false;
                $html .= '<span class="text-sm">Para <strong>' . formatoMillares($articulo['cantidad'], 3) . '</strong> del articulo <strong>' . $articulo['codigo'] . '</strong>. El stock es <strong>0,000</strong></span><br>';
                $this->addError('cantidad.' . $articulo['i'], 'error');
            }
        }

        if ($procesar){

            //Guardamos el Despacho
            $despacho = new Despacho();
            $despacho->empresas_id = $this->empresas_id;
            $despacho->codigo = $this->codigo;
            $despacho->descripcion = $this->descripcion;
            $despacho->fecha = $this->fecha;
            if ($this->segmentos_id){
                $despacho->segmentos_id = $this->segmentos_id;
            }
            $despacho->save();

            $this->setNexCodigo($this->proximo_codigo['id'], $this->proximo_codigo['proximo']);

            for ($i = 0; $i < $this->contador; $i++) {
                $detalle = new DespDetalle();
                $detalle->despachos_id = $despacho->id;
                $detalle->recetas_id = $this->idReceta[$i];
                $detalle->almacenes_id = $this->idAlmacen[$i];
                $detalle->cantidad = $this->cantidad[$i];
                $detalle->save();
            }

            //descontamos el stock
            $this->descontarStock($listarArticulos);

            $this->show($despacho->id);
            $this->alert('success', 'Despacho Guardado Correctamente.');

        }else{
            //mando un alerta
            $this->alert('warning', '¡Stock Insuficiente!', [
                'position' => 'center',
                'timer' => '',
                'toast' => false,
                'html' => $html,
                'showConfirmButton' => true,
                'onConfirmed' => '',
                'confirmButtonText' => 'OK',
            ]);
        }



    }

    #[On('show')]
    public function show($id)
    {
        $this->limpiar();
        $despacho = Despacho::find($id);
        $this->edit = true;
        $this->view = "show";
        $this->footer = true;
        $this->despachos_id = $despacho->id;
        $this->codigo = $despacho->codigo;
        $this->fecha = $despacho->fecha;
        $this->descripcion = $despacho->descripcion;
        $this->segmentos_id = $despacho->segmentos_id;
        if ($this->segmentos_id){
            $this->verSegmento = $despacho->segmento->descripcion;
        }
        $this->estatus = $despacho->estatus;
        $this->impreso = $despacho->impreso;

        $this->listarDetalles = DespDetalle::where('despachos_id', $this->despachos_id)->get();
        $this->contador = 0;
        foreach ($this->listarDetalles as $detalle){
            $this->idReceta[$this->contador] = $detalle->recetas_id;
            $this->codigoReceta[$this->contador] = $detalle->receta->codigo;
            $this->classReceta[$this->contador] = null;
            $this->descripcionReceta[$this->contador] = mb_strtoupper($detalle->receta->descripcion);
            $this->cantidad[$this->contador] = $detalle->cantidad;
            $this->detalles_id[$this->contador] = $detalle->id;
            $this->idAlmacen[$this->contador] = $detalle->almacenes_id;
            $this->codigoAlmacen[$this->contador] = $detalle->almacen->codigo;
            $this->classAlmacen[$this->contador] = null;
            $this->contador++;
        }
    }

    public function update()
    {
        $this->validate();

        if (empty($this->codigo)) {
            $this->codigo = $this->proximo_codigo['formato'] . cerosIzquierda($this->proximo_codigo['proximo'], numSizeCodigo());
        }

        if (empty($this->fecha)) {
            $this->fecha = date("Y-m-d H:i");
        }

        $procesar_despacho = false;

        //comprobamos cambios en tabla Despachos
        $despacho = Despacho::find($this->despachos_id);
        $db_codigo = $despacho->codigo;
        $db_descripcion = $despacho->descripcion;
        $db_fecha = $despacho->fecha;
        $db_segmentos_id = $despacho->segmentos_id;

        if ($db_codigo != $this->codigo){
            $procesar_despacho = true;
            $despacho->codigo = $this->codigo;
        }

        if ($db_fecha != $this->fecha){
            $procesar_despacho = true;
            $despacho->fecha = $this->fecha;
        }

        if ($db_descripcion != $this->descripcion){
            $procesar_despacho = true;
            $despacho->descripcion = $this->descripcion;
        }

        if ($db_segmentos_id != $this->segmentos_id){
            $procesar_despacho = true;
            if ($this->segmentos_id){
                $despacho->segmentos_id = $this->segmentos_id;
            }else{
                $despacho->segmentos_id = null;
            }
        }

        //***** Detalles ******
        $itemEliminados = array();
        $revisados = array();
        $procesar_detalles = array();
        $error = array();
        $html = null;

        //validamos los item eliminados
        if (!empty($this->borraritems)) {
            foreach ($this->borraritems as $item) {
                $detalles = DespDetalle::find($item['id']);
                $db_recetas_id = $detalles->recetas_id;
                $db_almacenes_id = $detalles->almacenes_id;
                $db_cantidad = $detalles->cantidad;
                //articulos
                $this->listarReceta($db_recetas_id, $db_almacenes_id, $db_cantidad);
            }
            $itemEliminados = $this->listarArticulos;
        }

        //validamos la nueva grilla
        $this->reset('listarArticulos');
        for ($i = 0; $i < $this->contador; $i++) {
            //recetas
            $idReceta = $this->idReceta[$i];
            $almacenes_id = $this->idAlmacen[$i];
            $cantidadReceta = $this->cantidad[$i];
            $detalles_id = $this->detalles_id[$i];
            if ($detalles_id){
                $detalles = DespDetalle::find($detalles_id);
                $db_recetas_id = $detalles->recetas_id;
                $db_almacenes_id = $detalles->almacenes_id;
                $db_cantidad = $detalles->cantidad;

                $diferencias_stock = false;

                if ($db_recetas_id != $idReceta){
                    $diferencias_stock = true;
                }

                if ($db_almacenes_id != $almacenes_id){
                    $diferencias_stock = true;
                }

                if ($db_cantidad != $cantidadReceta){
                    $diferencias_stock = true;
                }

                if ($diferencias_stock){
                    $procesar_detalles[$i] = true;
                }

                $this->listarReceta($db_recetas_id, $db_almacenes_id, $db_cantidad);

            }else{
                //nuevo renglon
                $procesar_detalles[$i] = true;
            }
        }

        $revisados = $this->listarArticulos;

        //$this->reset('listarArticulos');
        //para validar stock
        $listarArticulos = $this->listarArticulos();

        //Validamos el Stock
        foreach ($listarArticulos as $articulo){

            $cantidadEliminados = 0;
            $cantidadRevisados = 0;

            $stock = Stock::where('empresas_id', $this->empresas_id)
                ->where('articulos_id', $articulo['articulos_id'])
                ->where('almacenes_id', $articulo['almacenes_id'])
                ->where('unidades_id', $articulo['unidades_id'])
                ->first();
            if ($stock) {

                $disponible = $stock->disponible;

                $buscarEliminados = array_key_exists(mb_strtoupper($articulo['codigo'].'_'.$articulo['almacenes_id']), $itemEliminados);
                if ($buscarEliminados){
                    $cantidadEliminados = $itemEliminados[$articulo['codigo'].'_'.$articulo['almacenes_id']]['cantidad'];
                }

                $buscarRevisados = array_key_exists(mb_strtoupper($articulo['codigo'].'_'.$articulo['almacenes_id']), $revisados);
                if ($buscarRevisados){
                    $cantidadRevisados = $revisados[$articulo['codigo'].'_'.$articulo['almacenes_id']]['cantidad'];
                }

                $disponible = $disponible + ($cantidadEliminados + $cantidadRevisados);

                if ($articulo['cantidad'] > $disponible) {
                    $error[$articulo['i']] = true;
                    $mostrarCantidad = $articulo['cantidad'] - ($cantidadEliminados + $cantidadRevisados);
                    $mostrarStock = $disponible - ($cantidadEliminados + $cantidadRevisados);
                    $html .= '<span class="text-sm">Para <strong>' . formatoMillares($mostrarCantidad, 3) . ' '.$articulo['unidad'].' </strong> del articulo <strong>' . $articulo['codigo'] . '</strong>. El stock es <strong>' . formatoMillares($mostrarStock, 3) . '</strong></span><br>';
                    $this->addError('cantidad.' . $articulo['i'], 'error');
                }

            } else {
                $error[$articulo['i']] = true;
                $html .= '<span class="text-sm">Para <strong>' . formatoMillares($articulo['cantidad'], 3) . '</strong> del articulo <strong>' . $articulo['codigo'] . '</strong>. El stock es <strong>0,000</strong></span><br>';
                $this->addError('cantidad.' . $articulo['i'], 'error');
            }
        }

        //procesamos
        if (($procesar_despacho || !empty($this->borraritems) || !empty($procesar_detalles)) && empty($error)){

            //guardar Despacho
            if ($procesar_despacho){
                $despacho->save();
            }

            //item eliminados
            if (!empty($this->borraritems)){
                //devolvemos el Stock
                foreach ($itemEliminados as $articulo){
                    $stock = Stock::where('empresas_id', $this->empresas_id)
                        ->where('articulos_id', $articulo['articulos_id'])
                        ->where('almacenes_id', $articulo['almacenes_id'])
                        ->where('unidades_id', $articulo['unidades_id'])
                        ->first();
                    if ($stock) {
                        $comprometido = $stock->comprometido;
                        $disponible = $stock->disponible;
                        $stock->disponible = $disponible + $articulo['cantidad'];
                        $stock->actual = $comprometido + $stock->disponible;
                        $stock->save();
                    }
                }
                //elimino el Item
                foreach ($this->borraritems as $item) {
                    $detalles = DespDetalle::find($item['id']);
                    $detalles->delete();
                }
            }

            //procesamos Detalles
            if (!empty($procesar_detalles)){

                //item Revisados
                if (!empty($revisados)){
                    //devolvemos el Stock
                    foreach ($revisados as $articulo){
                        $stock = Stock::where('empresas_id', $this->empresas_id)
                            ->where('articulos_id', $articulo['articulos_id'])
                            ->where('almacenes_id', $articulo['almacenes_id'])
                            ->where('unidades_id', $articulo['unidades_id'])
                            ->first();
                        if ($stock) {
                            $comprometido = $stock->comprometido;
                            $disponible = $stock->disponible;
                            $stock->disponible = $disponible + $articulo['cantidad'];
                            $stock->actual = $comprometido + $stock->disponible;
                            $stock->save();
                        }
                    }
                }

                //guardar Detalles
                for ($i = 0; $i < $this->contador; $i++) {
                    $detalles_id = $this->detalles_id[$i];
                    if ($detalles_id){
                        $detalle = DespDetalle::find($detalles_id);
                    }else{
                        $detalle = new DespDetalle();
                    }
                    $detalle->despachos_id = $despacho->id;
                    $detalle->recetas_id = $this->idReceta[$i];
                    $detalle->almacenes_id = $this->idAlmacen[$i];
                    $detalle->cantidad = $this->cantidad[$i];
                    $detalle->save();
                }
                //descontamos el stock
                $this->descontarStock($listarArticulos);
            }

            $this->show($despacho->id);
            $this->alert('success', 'guardar');

        }else{

            if (!empty($error)){
                $this->alert('warning', '¡Stock Insuficiente!', [
                    'position' => 'center',
                    'timer' => '',
                    'toast' => false,
                    'html' => $html,
                    'showConfirmButton' => true,
                    'onConfirmed' => '',
                    'confirmButtonText' => 'OK',
                ]);
            }else{
                $this->alert('info', 'No se realizo ningún cambio.');
                $this->show($this->despachos_id);
            }
        }

    }

    public function destroy($opcion = "delete")
    {
        $this->opcionDestroy = $opcion;
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
        $despacho = Despacho::find($this->despachos_id);
        $estatus = $despacho->estatus;

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
            if ($estatus){

                $detalles = DespDetalle::where('despachos_id', $this->despachos_id)->get();
                foreach ($detalles as $detalle){
                    $idReceta = $detalle->recetas_id;
                    $idAlmacen = $detalle->almacenes_id;
                    $cantidadReceta = $detalle->cantidad;
                    $this->listarReceta($idReceta, $idAlmacen, $cantidadReceta);
                }
                //devolvemos el Stock
                foreach ($this->listarArticulos as $articulo){
                    $stock = Stock::where('empresas_id', $this->empresas_id)
                        ->where('articulos_id', $articulo['articulos_id'])
                        ->where('almacenes_id', $articulo['almacenes_id'])
                        ->where('unidades_id', $articulo['unidades_id'])
                        ->first();
                    if ($stock) {
                        $comprometido = $stock->comprometido;
                        $disponible = $stock->disponible;
                        $stock->disponible = $disponible + $articulo['cantidad'];
                        $stock->actual = $comprometido + $stock->disponible;
                        $stock->save();
                    }
                }

                $despacho->estatus = 0;

            }else{
                $despacho->codigo = "*".$despacho->codigo;
            }

            $despacho->save();

            if ($this->opcionDestroy == "delete") {
                $despacho->delete();
                $this->edit = false;
                $this->limpiar();
                $this->alert('success', 'Despacho Eliminado.');
            }else{
                $this->show($this->despachos_id);
                $this->alert('success', 'Despacho Anulado.');
            }
        }
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
            $this->idAlmacen[$this->contador] = null;
            $this->codigoAlmacen[$this->contador] = null;
            $this->classAlmacen[$this->contador] = null;
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
                $this->idAlmacen[$i] = $this->idAlmacen[$i + 1];
                $this->codigoAlmacen[$i] = $this->codigoAlmacen[$i + 1];
                $this->classAlmacen[$i] = $this->classAlmacen[$i + 1];
            }
            $this->contador--;
            unset($this->idReceta[$this->contador]);
            unset($this->codigoReceta[$this->contador]);
            unset($this->classReceta[$this->contador]);
            unset($this->descripcionReceta[$this->contador]);
            unset($this->cantidad[$this->contador]);
            unset($this->detalles_id[$this->contador]);
            unset($this->idAlmacen[$this->contador]);
            unset($this->codigoAlmacen[$this->contador]);
            unset($this->classAlmacen[$this->contador]);
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

    public function updatedCodigoAlmacen()
    {
        foreach ($this->codigoAlmacen as $key => $value) {
            if ($value) {
                $almacen = Almacen::where('codigo', $value)->where('empresas_id', $this->empresas_id)->first();
                if ($almacen) {
                    $this->resetErrorBag('codigoAlmacen.' . $key);
                    $this->idAlmacen[$key] = $almacen->id;
                    //$this->ajuste_almacenes_tipo[$key] = $almacen->tipo;
                    $this->classAlmacen[$key] = "is-valid";
                } else {
                    $this->idAlmacen[$key] = null;
                    //$this->ajuste_almacenes_tipo[$key] = null;
                    $this->classAlmacen[$key] = "is-invalid";
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
            $this->show($this->despachos_id);
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
        $this->proximo_codigo = $this->getNextCodigo();
    }

    protected function getNextCodigo(): array
    {
        $codigo = array();

        $parametro = Parametro::where("nombre", "proximo_codigo_despachos")->where('tabla_id', $this->empresas_id)->first();
        if ($parametro) {
            $codigo['id'] = $parametro->id;
            $codigo['proximo'] = (int)$parametro->valor;
        }else{
            $codigo['id'] = null;
            $codigo['proximo'] = 1;
        }

        $parametro = Parametro::where("nombre", "formato_codigo_despachos")->where('tabla_id', $this->empresas_id)->first();
        if ($parametro) {
            $codigo['formato'] = $parametro->valor;
        }else{
            $codigo['formato'] = 'N'.$this->empresas_id.'-';
        }

        $parametro = Parametro::where("nombre", "editable_codigo_despachos")->where('tabla_id', $this->empresas_id)->first();
        if ($parametro){
            if ($parametro->valor == 1){
                $codigo['editable'] = true;
            }else{
                $codigo['editable'] = false;
            }
        }else{
            $codigo['editable'] = false;
        }

        $parametro = Parametro::where("nombre", "editable_fecha_despachos")->where('tabla_id', $this->empresas_id)->first();
        if ($parametro){
            if ($parametro->valor == 1){
                $codigo['editable_fecha'] = true;
            }else{
                $codigo['editable_fecha'] = false;
            }
        }else{
            $codigo['editable_fecha'] = false;
        }

        return $codigo;

    }

    protected function setNexCodigo($id, $proximo)
    {
        if (empty($id)){
            //nuevo
            $parametro = new Parametro();
            $parametro->tabla_id = $this->empresas_id;
            $parametro->nombre = "proximo_codigo_despachos";
        }else{
            //edito
            $parametro = Parametro::find($id);
        }
        $parametro->valor = $proximo + 1;
        $parametro->save();
    }

    protected function listarArticulos(): array
    {
        $listarArticulos = [];
        for ($i = 0; $i < $this->contador; $i++) {
            //recetas
            $idReceta = $this->idReceta[$i];
            $almacenes_id = $this->idAlmacen[$i];
            $cantidadReceta = $this->cantidad[$i];

            //articulos
            $detallesReceta = ReceDetalle::where('recetas_id', $idReceta)->get();
            foreach ($detallesReceta as $articulo) {
                $idArticulo = $articulo->articulos_id;
                $idUnidad = $articulo->unidades_id;
                $cantidadArticulo = $articulo->cantidad;

                $buscar = array_key_exists(mb_strtoupper($articulo->articulo->codigo).'_'.$almacenes_id, $listarArticulos);

                if (!$buscar) {
                    $listarArticulos[''. mb_strtoupper($articulo->articulo->codigo) .'_'.$almacenes_id] = [
                        'articulos_id' => $idArticulo,
                        'codigo' => mb_strtoupper($articulo->articulo->codigo),
                        'descripcion' => mb_strtoupper($articulo->articulo->descripcion),
                        'unidades_id' => $idUnidad,
                        'unidad' => mb_strtoupper($articulo->articulo->unidad->codigo),
                        'cantidad' => $cantidadReceta * $cantidadArticulo,
                        'almacenes_id' => $almacenes_id,
                        'i' => $i
                    ];
                } else {
                    $cantidad = $listarArticulos[''.mb_strtoupper($articulo->articulo->codigo).'_'.$almacenes_id]['cantidad'];
                    $listarArticulos[''.mb_strtoupper($articulo->articulo->codigo).'_'.$almacenes_id]['cantidad'] = $cantidad + ($cantidadReceta * $cantidadArticulo);
                }
            }
        }
        return $listarArticulos;
    }

    public function listarReceta($recetas_id, $almacenes_id, $cantidadReceta)
    {
        //articulos
        $detallesReceta = ReceDetalle::where('recetas_id', $recetas_id)->get();
        foreach ($detallesReceta as $articulo) {
            $idArticulo = $articulo->articulos_id;
            $idUnidad = $articulo->unidades_id;
            $cantidadArticulo = $articulo->cantidad;

            $buscar = array_key_exists(mb_strtoupper($articulo->articulo->codigo.'_'.$almacenes_id), $this->listarArticulos);

            if (!$buscar) {
                $this->listarArticulos[''.mb_strtoupper($articulo->articulo->codigo).'_'.$almacenes_id] = [
                    'articulos_id' => $idArticulo,
                    'unidades_id' => $idUnidad,
                    'almacenes_id' => $almacenes_id,
                    'cantidad' => $cantidadReceta * $cantidadArticulo,
                ];
            } else {
                $cantidad = $this->listarArticulos[''.mb_strtoupper($articulo->articulo->codigo).'_'.$almacenes_id]['cantidad'];
                $this->listarArticulos[''.mb_strtoupper($articulo->articulo->codigo).'_'.$almacenes_id]['cantidad'] = $cantidad + ($cantidadReceta * $cantidadArticulo);
            }
        }
    }

    protected function descontarStock($listarArticulos)
    {
        foreach ($listarArticulos as $articulo){
            $stock = Stock::where('empresas_id', $this->empresas_id)
                ->where('articulos_id', $articulo['articulos_id'])
                ->where('almacenes_id', $articulo['almacenes_id'])
                ->where('unidades_id', $articulo['unidades_id'])
                ->first();
            if ($stock) {
                $comprometido = $stock->comprometido;
                $disponible = $stock->disponible;
                $stock->disponible = $disponible - $articulo['cantidad'];
                $stock->actual = $comprometido + $stock->disponible;
                $stock->save();
            }
        }
    }

    public function actualizar()
    {
        $this->reset(['despachos_id', 'edit']);
        $this->limpiar();
    }

}
