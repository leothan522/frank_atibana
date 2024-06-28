<?php

namespace App\Livewire\Dashboard;

use App\Models\AjusDetalle;
use App\Models\AjusSegmento;
use App\Models\Ajuste;
use App\Models\AjusTipo;
use App\Models\Almacen;
use App\Models\Articulo;
use App\Models\ArtUnid;
use App\Models\Parametro;
use App\Models\Stock;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class AjustesComponent extends Component
{
    use LivewireAlert;

    public $rows = 0, $numero = 14, $tableStyle = false;
    public $empresas_id, $keyword, $editar = false, $footer, $view;
    public $nuevo = false, $btn_nuevo = true, $btn_editar = false, $btn_cancelar = false;
    public $ajustes_id, $codigo, $fecha, $descripcion, $estatus, $segmentos_id, $verSegmento, $listarDetalles,  $opcionDestroy;
    public $contador = 1, $ajusteTipo = [], $classTipo = [], $ajusteArticulo = [], $classArticulo = [], $ajusteDescripcion = [],
        $ajusteUnidad = [], $selectUnidad = [], $ajusteAlmacen = [], $classAlmacen = [], $ajusteCantidad = [],
        $ajuste_tipos_id = [], $ajuste_articulos_id = [], $ajuste_almacenes_id = [], $ajuste_tipos_tipo = [],
        $ajuste_almacenes_tipo = [],  $ajusteListarArticulos, $detalles_id = [];
    public $keywordArticulos, $item, $borraritems = [];
    public $proximo_codigo;

    public function mount()
    {
        $this->setLimit();
    }

    public function render()
    {
        //$this->proximo_codigo = nextCodigoAjuste($this->empresas_id);

        $ajustes = Ajuste::buscar($this->keyword)
            ->where('empresas_id', $this->empresas_id)
            ->orderBy('fecha', 'desc')
            ->orderBy('codigo', 'desc')
            ->limit($this->rows)
            ->get();
        $rowsAjustes = Ajuste::count();
        $selectSegmentos = AjusSegmento::orderBy('id', 'ASC')->get();

        if ($rowsAjustes > $this->numero) {
            $this->tableStyle = true;
        }

        return view('livewire.dashboard.ajustes-component')
            ->with('listarAjustes', $ajustes)
            ->with('rowsAjustes', $rowsAjustes)
            ->with('selectSegmentos', $selectSegmentos);
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

    #[On('getEmpresaAjuste')]
    public function getEmpresaAjuste($empresaID)
    {
        $this->empresas_id = $empresaID;
        $this->limpiarAjustes();
        $this->reset(['keyword', 'ajustes_id']);
    }

    public function limpiarAjustes()
    {
        $this->reset([
            'view', 'footer', 'nuevo', 'btn_nuevo', 'btn_editar', 'btn_cancelar',
            'contador', 'codigo', 'descripcion', 'fecha',
            'ajusteTipo', 'classTipo', 'ajusteArticulo', 'classArticulo', 'ajusteDescripcion', 'ajusteUnidad',
            'selectUnidad', 'ajusteAlmacen', 'ajusteCantidad', 'ajusteListarArticulos', 'keywordArticulos', 'item',
            'ajuste_tipos_id', 'ajuste_articulos_id', 'ajuste_almacenes_id', 'ajuste_almacenes_tipo',
            'listarDetalles', 'detalles_id', 'borraritems', 'estatus',
            'segmentos_id', 'verSegmento'
        ]);
        $this->resetErrorBag();
    }

    public function create()
    {
        $this->limpiarAjustes();
        $this->nuevo = true;
        $this->view = "form";
        $this->btn_nuevo = false;
        $this->btn_cancelar = true;
        $this->btn_editar = false;
        $this->footer = false;
        $this->proximo_codigo = $this->getNextCodigo();

        $this->ajusteTipo[0] = null;
        $this->classTipo[0] = null;
        $this->ajusteArticulo[0] = null;
        $this->classArticulo[0] = null;
        $this->ajusteDescripcion[0] = null;
        $this->selectUnidad[0] = array();
        $this->ajusteUnidad[0] = null;
        $this->ajusteAlmacen[0] = null;
        $this->classAlmacen[0] = null;
        $this->ajusteCantidad[0] = null;
        $this->detalles_id[0] = null;
    }

    public function btnCancelar()
    {
        $this->limpiarAjustes();
        if ($this->ajustes_id) {
            //show ajuste
            $this->show($this->ajustes_id);
        }
    }

    public function btnContador($opcion)
    {
        if ($opcion == "add") {
            $this->ajusteTipo[$this->contador] = null;
            $this->ajuste_tipos_tipo[$this->contador] = null;
            $this->classTipo[$this->contador] = null;
            $this->ajusteArticulo[$this->contador] = null;
            $this->ajuste_articulos_id[$this->contador] = null;
            $this->classArticulo[$this->contador] = null;
            $this->ajusteDescripcion[$this->contador] = null;
            $this->selectUnidad[$this->contador] = array();
            $this->ajusteUnidad[$this->contador] = null;
            $this->ajusteAlmacen[$this->contador] = null;
            $this->ajuste_almacenes_id[$this->contador] = null;
            $this->classAlmacen[$this->contador] = null;
            $this->ajusteCantidad[$this->contador] = null;
            $this->detalles_id[$this->contador] = null;
            $this->contador++;
        } else {

            if ($this->detalles_id[$opcion]) {
                $this->borraritems[] = [
                    'id' => $this->detalles_id[$opcion]
                ];
            }

            for ($i = $opcion; $i < $this->contador - 1; $i++) {
                $this->ajusteTipo[$i] = $this->ajusteTipo[$i + 1];
                $this->ajuste_tipos_tipo[$i] = $this->ajuste_tipos_tipo[$i + 1];
                $this->classTipo[$i] = $this->classTipo[$i + 1];
                $this->ajusteArticulo[$i] = $this->ajusteArticulo[$i + 1];
                $this->ajuste_articulos_id[$i] = $this->ajuste_articulos_id[$i + 1];
                $this->classArticulo[$i] = $this->classArticulo[$i + 1];
                $this->ajusteDescripcion[$i] = $this->ajusteDescripcion[$i + 1];
                $this->selectUnidad[$i] = $this->selectUnidad[$i + 1];
                $this->ajusteUnidad[$i] = $this->ajusteUnidad[$i + 1];
                $this->ajusteAlmacen[$i] = $this->ajusteAlmacen[$i + 1];
                $this->ajuste_almacenes_id[$i] = $this->ajuste_almacenes_id[$i + 1];
                $this->classAlmacen[$i] = $this->classAlmacen[$i + 1];
                $this->ajusteCantidad[$i] = $this->ajusteCantidad[$i + 1];
                $this->detalles_id[$i] = $this->detalles_id[$i + 1];
            }
            $this->contador--;
            unset($this->ajusteTipo[$this->contador]);
            unset($this->classTipo[$this->contador]);
            unset($this->ajusteArticulo[$this->contador]);
            unset($this->classArticulo[$this->contador]);
            unset($this->ajusteDescripcion[$this->contador]);
            unset($this->selectUnidad[$this->contador]);
            unset($this->ajusteUnidad[$this->contador]);
            unset($this->ajusteAlmacen[$this->contador]);
            unset($this->classAlmacen[$this->contador]);
            unset($this->ajusteCantidad[$this->contador]);
            unset($this->detalles_id[$this->contador]);
        }
    }

    protected function rules()
    {
        return [
            'codigo' => ['nullable', 'min:4', 'alpha_dash:ascii', Rule::unique('ajustes', 'codigo')->ignore($this->ajustes_id)],
            'fecha' => 'nullable',
            'descripcion' => 'required|min:4',
            /*'segmentos_id' => 'required',*/
            'ajusteTipo.*' => ['required', Rule::exists('ajustes_tipos', 'codigo')],
            'ajusteArticulo.*' => ['required', Rule::exists('articulos', 'codigo')],
            'ajusteUnidad.*' => 'required',
            'ajusteAlmacen.*' => ['required', Rule::exists('almacenes', 'codigo')],
            'ajusteCantidad.*' => 'required'
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

        for ($i = 0; $i < $this->contador; $i++) {
            if ($this->ajuste_tipos_tipo[$i] == 2) {
                $stock = Stock::where('empresas_id', $this->empresas_id)
                    ->where('articulos_id', $this->ajuste_articulos_id[$i])
                    ->where('almacenes_id', $this->ajuste_almacenes_id[$i])
                    ->where('unidades_id', $this->ajusteUnidad[$i])
                    ->first();
                if ($stock) {
                    $disponible = $stock->disponible;
                    if ($this->ajusteCantidad[$i] > $disponible) {
                        $procesar = false;
                        $html .= 'Para <strong>' . formatoMillares($this->ajusteCantidad[$i], 3) . '</strong> del articulo <strong>' . $this->ajusteArticulo[$i] . '</strong>. el stock actual es <strong>' . formatoMillares($disponible, 3) . '</strong><br>';
                        $this->addError('ajusteCantidad.' . $i, 'error');
                    }
                } else {
                    $procesar = false;
                    $html .= 'Para <strong>' . formatoMillares($this->ajusteCantidad[$i], 3) . '</strong> del articulo <strong>' . $this->ajusteArticulo[$i] . '</strong>. el stock actual es <strong>0,000</strong><br>';
                    $this->addError('ajusteCantidad.' . $i, 'error');
                }
            }
        }

        if ($procesar) {

            $ajuste = new Ajuste();
            $ajuste->empresas_id = $this->empresas_id;
            $ajuste->codigo = $this->codigo;
            $ajuste->descripcion = $this->descripcion;
            $ajuste->segmentos_id = $this->segmentos_id;
            //$date = new \DateTime($this->fecha);
            //$ajuste->fecha = $date->format('Y-m-d H:i');
            $ajuste->fecha = $this->fecha;
            $ajuste->save();

            $this->setNexCodigo($this->proximo_codigo['id'], $this->proximo_codigo['proximo']);

            for ($i = 0; $i < $this->contador; $i++) {
                $detalles = new AjusDetalle();
                $detalles->ajustes_id = $ajuste->id;
                $detalles->tipos_id = $this->ajuste_tipos_id[$i];
                $detalles->articulos_id = $this->ajuste_articulos_id[$i];
                $detalles->almacenes_id = $this->ajuste_almacenes_id[$i];
                $detalles->unidades_id = $this->ajusteUnidad[$i];
                $detalles->cantidad = $this->ajusteCantidad[$i];
                $detalles->save();
                $exite = Stock::where('empresas_id', $this->empresas_id)
                    ->where('articulos_id', $this->ajuste_articulos_id[$i])
                    ->where('almacenes_id', $this->ajuste_almacenes_id[$i])
                    ->where('unidades_id', $this->ajusteUnidad[$i])
                    ->first();
                if ($exite) {
                    //edito
                    $stock = Stock::find($exite->id);
                    $compometido = $stock->comprometido;
                    $disponible = $stock->disponible;
                    if ($this->ajuste_tipos_tipo[$i] == 1) {
                        //sumo entrada
                        $stock->disponible = $disponible + $this->ajusteCantidad[$i];
                    } else {
                        //resto salida
                        $stock->disponible = $disponible - $this->ajusteCantidad[$i];
                    }
                    $stock->actual = $compometido + $stock->disponible;
                    $stock->save();
                } else {
                    //nuevo
                    $stock = new Stock();
                    $stock->empresas_id = $this->empresas_id;
                    $stock->articulos_id = $this->ajuste_articulos_id[$i];
                    $stock->almacenes_id = $this->ajuste_almacenes_id[$i];
                    $stock->unidades_id = $this->ajusteUnidad[$i];
                    $stock->actual = $this->ajusteCantidad[$i];
                    $stock->comprometido = 0;
                    $stock->disponible = $this->ajusteCantidad[$i];
                    $stock->vendido = 0;
                    $stock->almacen_principal = $this->ajuste_almacenes_tipo[$i];
                    $stock->save();
                }
            }
            $this->show($ajuste->id);
            $this->dispatch('showStock')->to(StockComponent::class);
            $this->alert('success', 'Ajuste Guardado Correctamente.');
        } else {
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

    public function updatedAjusteTipo()
    {
        foreach ($this->ajusteTipo as $key => $value) {
            if ($value) {
                $tipo = AjusTipo::where('codigo', $value)->first();
                if ($tipo) {
                    $this->ajuste_tipos_id[$key] = $tipo->id;
                    $this->ajuste_tipos_tipo[$key] = $tipo->tipo;
                    $this->classTipo[$key] = "is-valid";
                    $this->resetErrorBag('ajusteTipo.' . $key);
                } else {
                    $this->classTipo[$key] = "is-invalid";
                    $this->ajuste_tipos_id[$key] = null;
                    $this->ajuste_tipos_tipo[$key] = null;
                }
            }
        }
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

    public function updatedAjusteAlmacen()
    {
        foreach ($this->ajusteAlmacen as $key => $value) {
            if ($value) {
                $almacen = Almacen::where('codigo', $value)->where('empresas_id', $this->empresas_id)->first();
                if ($almacen) {
                    $this->resetErrorBag('ajusteAlmacen.' . $key);
                    $this->ajuste_almacenes_id[$key] = $almacen->id;
                    $this->ajuste_almacenes_tipo[$key] = $almacen->tipo;
                    $this->classAlmacen[$key] = "is-valid";
                } else {
                    $this->ajuste_almacenes_id[$key] = null;
                    $this->ajuste_almacenes_tipo[$key] = null;
                    $this->classAlmacen[$key] = "is-invalid";
                }
            }
        }
    }

    public function itemTemporal($int)
    {
        $this->item = $int;
    }

    public function buscarArticulos()
    {
        $this->ajusteListarArticulos = Articulo::buscar($this->keywordArticulos)
            ->where('empresas_id', $this->empresas_id)
            ->where('estatus', 1)
            ->limit(100)
            ->get();
    }

    public function selectArticulo($codigo)
    {
        $this->ajusteArticulo[$this->item] = $codigo;
        $this->updatedAjusteArticulo();
    }

    #[On('show')]
    public function show($id)
    {
        $this->limpiarAjustes();
        $this->ajustes_id = $id;
        $this->btn_editar = true;
        $this->footer = true;
        $ajuste = Ajuste::find($this->ajustes_id);
        $this->codigo = $ajuste->codigo;
        $this->fecha = $ajuste->fecha;
        $this->descripcion = $ajuste->descripcion;
        $this->segmentos_id = $ajuste->segmentos_id;
        if ($this->segmentos_id){
            $this->verSegmento = $ajuste->segmentos->descripcion;
        }
        $this->estatus = $ajuste->estatus;
        $this->listarDetalles = AjusDetalle::where('ajustes_id', $this->ajustes_id)->get();
        $this->contador = AjusDetalle::where('ajustes_id', $this->ajustes_id)->count();
        $this->view = 'show';
    }

    public function btnEditar()
    {
        $this->view = 'form';
        $this->nuevo = false;
        $this->btn_editar = false;
        $this->btn_cancelar = true;
        $this->footer = false;
        $this->proximo_codigo = $this->getNextCodigo();

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
            $this->ajusteTipo[$i] = $detalle->tipo->codigo;
            $this->ajuste_tipos_id[$i] = $detalle->tipo->id;
            $this->ajuste_tipos_tipo[$i] = $detalle->tipo->tipo;
            $this->classTipo[$i] = null;
            $this->ajusteArticulo[$i] = $detalle->articulo->codigo;
            $this->ajuste_articulos_id[$i] = $detalle->articulos_id;
            $this->classArticulo[$i] = null;
            $this->ajusteDescripcion[$i] = $detalle->articulo->descripcion;
            $this->selectUnidad[$i] = $array;
            $this->ajusteUnidad[$i] = $detalle->unidades_id;
            $this->ajusteAlmacen[$i] = $detalle->almacen->codigo;
            $this->ajuste_almacenes_id[$i] = $detalle->almacenes_id;
            $this->ajuste_almacenes_tipo[$i] = $detalle->almacen->tipo;
            $this->classAlmacen[$i] = null;
            $this->ajusteCantidad[$i] = $detalle->cantidad;
            $this->detalles_id[$i] = $detalle->id;
            $i++;
        }
    }

    public function update()
    {

        $this->validate();

        if (empty($this->codigo)) {
            $this->codigo = $this->proximo_codigo['formato'] . cerosIzquierda($this->proximo_codigo['proximo'], numSizeCodigo());
        }

        if (empty($this->fecha)) {
            $this->fecha = date("Y-m-d H:i:s");
        }

        $procesar_ajuste = false;
        $html = null;

        $ajuste = Ajuste::find($this->ajustes_id);
        $db_codigo = $ajuste->codigo;
        $db_fecha = $ajuste->fecha;
        $db_descripcion = $ajuste->descripcion;
        $db_segmento = $ajuste->segmentos_id;

        if ($db_codigo != $this->codigo) {
            $procesar_ajuste = true;
            $ajuste->codigo = $this->codigo;
        }

        if ($db_fecha != $this->fecha) {
            $procesar_ajuste = true;
            $ajuste->fecha = $this->fecha;
        }

        if ($db_descripcion != $this->descripcion) {
            $procesar_ajuste = true;
            $ajuste->descripcion = $this->descripcion;
        }

        if ($db_segmento != $this->segmentos_id) {
            $procesar_ajuste = true;
            if ($this->segmentos_id){
                $ajuste->segmentos_id = $this->segmentos_id;
            }else{
                $ajuste->segmentos_id = null;
            }
        }

        //***** Detalles ******
        $itemEliminados = array();
        $procesar_detalles = array();
        $revisados = array();
        $error = array();
        $success = array();

        if (!empty($this->borraritems)) {
            foreach ($this->borraritems as $item) {
                $detalles = AjusDetalle::find($item['id']);
                $db_articulo_id = $detalles->articulos_id;
                $db_almacen_id = $detalles->almacenes_id;
                $db_unidad_id = $detalles->unidades_id;
                $db_cantidad = $detalles->cantidad;
                $db_accion = $detalles->tipo->tipo;
                //me traigo el stock actual
                $stock = Stock::where('empresas_id', $this->empresas_id)
                    ->where('articulos_id', $db_articulo_id)
                    ->where('almacenes_id', $db_almacen_id)
                    ->where('unidades_id', $db_unidad_id)
                    ->first();
                if ($stock) {
                    //bien
                    $db_disponible = $stock->disponible;
                    $db_comprometido = $stock->comprometido;

                    $itemEliminados[] = [
                        'id' => $stock->id,
                        'accion' => $db_accion,
                        'cantidad' => $db_cantidad
                    ];


                    if ($db_accion == 1) {
                        //revierto la entrada
                        if ($db_disponible < $db_cantidad) {
                            $error[-1] = true;
                            $html .= '<span class="text-sm">Para <strong> - ' . formatoMillares($db_cantidad, 3) . '</strong> del articulo <strong>' . $detalles->articulo->codigo . '</strong>. el stock actual es <strong>' . $db_disponible . ' ' . $detalles->unidad->codigo . '</strong></span><br>';
                        }
                    }

                } else {
                    $error[-1] = true;
                    $html .= '<span class="text-sm">Para <strong> - ' . formatoMillares($db_cantidad, 3) . '</strong> del articulo <strong>' . $detalles->articulo->codigo . '</strong>. el stock actual es <strong>0,000 ' . $detalles->unidad->codigo . '</strong></span><br>';
                    //$this->addError('ajusteCantidad.' . $i, 'error');
                }
            }
        }


        for ($i = 0; $i < $this->contador; $i++) {

            $detalle_id = $this->detalles_id[$i];
            $tipo_id = $this->ajuste_tipos_id[$i];
            $accion = $this->ajuste_tipos_tipo[$i];
            $articulo_id = $this->ajuste_articulos_id[$i];
            $almacen_id = $this->ajuste_almacenes_id[$i];
            $unidad_id = $this->ajusteUnidad[$i];
            $cantidad = $this->ajusteCantidad[$i];

            if ($detalle_id) {
                //seguimos validando
                $detalles = AjusDetalle::find($detalle_id);
                $db_tipo_id = $detalles->tipos_id;
                $db_articulo_id = $detalles->articulos_id;
                $db_almacen_id = $detalles->almacenes_id;
                $db_unidad_id = $detalles->unidades_id;
                $db_unidad_codigo = $detalles->unidad->codigo;
                $db_cantidad = $detalles->cantidad;
                $db_accion = $detalles->tipo->tipo;

                $diferencias_stock = false;
                $diferencias_cantidad = false;
                $cambios = array();

                if ($db_tipo_id != $tipo_id) {
                    $diferencias_stock = true;
                }
                if ($db_articulo_id != $articulo_id) {
                    $diferencias_stock = true;
                }
                if ($db_almacen_id != $almacen_id) {
                    $diferencias_stock = true;
                }
                if ($db_unidad_id != $unidad_id) {
                    $diferencias_stock = true;
                }
                if ($db_cantidad != $cantidad) {
                    $diferencias_cantidad = true;
                }

                if ($diferencias_stock || $diferencias_cantidad) {
                    //me traigo el stock actual
                    $stock = Stock::where('empresas_id', $this->empresas_id)
                        ->where('articulos_id', $db_articulo_id)
                        ->where('almacenes_id', $db_almacen_id)
                        ->where('unidades_id', $db_unidad_id)
                        ->first();

                    if ($stock) {
                        //exite
                        $db_disponible = $stock->disponible;
                        $db_comprometido = $stock->comprometido;

                        if (!empty($itemEliminados)) {
                            foreach ($itemEliminados as $eliminado) {
                                if ($stock->id == $eliminado['id']) {
                                    if ($eliminado['accion'] == 1) {
                                        //retire la entrada
                                        $db_disponible = $db_disponible - $eliminado['cantidad'];
                                    } else {
                                        //retiro la salida
                                        $db_disponible = $db_disponible + $eliminado['cantidad'];
                                    }
                                }
                            }
                        }

                        //stock diferente misma cantidad
                        if ($diferencias_stock) {
                            //revierto el ajuste anterior
                            if ($db_accion == 1) {
                                //revierto entrada
                                if ($db_disponible >= $db_cantidad) {
                                    //seguimos
                                    $procesar_detalles[$i] = true;
                                    $revertido = $db_disponible - $db_cantidad;
                                } else {
                                    $revertido = null;
                                    $error[$i] = true;
                                    $html .= '<span class="text-sm">Para1 <strong> - ' . formatoMillares($this->ajusteCantidad[$i], 3) . '</strong> del articulo <strong>' . $stock->articulo->codigo . '</strong>. el stock actual es <strong>' . formatoMillares($db_disponible, 3) . ' ' . $db_unidad_codigo . '</strong></span><br>';
                                    $this->addError('ajusteCantidad.' . $i, 'error');
                                }
                            } else {
                                //revierto salida
                                $procesar_detalles[$i] = true;
                                $revertido = $db_disponible + $db_cantidad;
                            }

                            //procesamos lo nuevo
                            $db_id = $stock->id;
                            if ($db_articulo_id == $articulo_id && $db_almacen_id == $almacen_id && $db_unidad_id == $unidad_id) {
                                $db_disponible = $revertido;
                            } else {
                                $stock = Stock::where('empresas_id', $this->empresas_id)
                                    ->where('articulos_id', $articulo_id)
                                    ->where('almacenes_id', $almacen_id)
                                    ->where('unidades_id', $unidad_id)
                                    ->first();
                                if ($stock) {
                                    $db_comprometido = $stock->comprometido;
                                    $db_disponible = $stock->disponible;
                                }
                            }

                            if ($accion == 1) {

                                //entrada
                                if ($stock) {
                                    $disponible = $db_disponible + $cantidad;
                                    $actual = $disponible + $db_comprometido;
                                    //edito
                                    $cambios = [
                                        'accion' => 'editar_stock',
                                        'id' => $stock->id,
                                        'actual' => $actual,
                                        'disponible' => $disponible
                                    ];
                                } else {
                                    //nuevo
                                    $cambios = [
                                        'accion' => 'nuevo_stock',
                                        'articulo_id' => $articulo_id,
                                        'almacen_id' => $almacen_id,
                                        'unidad_id' => $unidad_id,
                                        'actual' => $cantidad,
                                        'disponible' => $cantidad,
                                        'almacen_pricipal' => $this->ajuste_almacenes_tipo[$i]
                                    ];
                                }

                            } else {
                                //salida
                                if ($stock) {
                                    if ($db_disponible >= $cantidad) {
                                        $disponible = $db_disponible - $cantidad;
                                        $actual = $disponible + $db_comprometido;
                                        $cambios = [
                                            'accion' => 'editar_stock',
                                            'id' => $stock->id,
                                            'actual' => $actual,
                                            'disponible' => $disponible
                                        ];
                                    } else {
                                        $error[$i] = true;
                                        $html .= 'Para <strong>2 - ' . formatoMillares($this->ajusteCantidad[$i], 3) . '</strong> del articulo <strong>' . $this->ajusteArticulo[$i] . '</strong>. el stock actual es <strong>' . formatoMillares($db_disponible, 3) . '</strong><br>';
                                        $this->addError('ajusteCantidad.' . $i, 'error');
                                    }
                                } else {
                                    $error[$i] = true;
                                    $html .= 'Para <strong>3 - ' . formatoMillares($this->ajusteCantidad[$i], 3) . '</strong> del articulo <strong>' . $this->ajusteArticulo[$i] . '</strong>. el stock actual es <strong>0,000</strong><br>';
                                    $this->addError('ajusteCantidad.' . $i, 'error');
                                }
                            }

                            //aplico los cambios
                            $revisados[$i] = [
                                'accion' => 'editar_stock',
                                'id' => $db_id,
                                'actual' => $revertido + $db_comprometido,
                                'disponible' => $revertido,
                                'cambios' => $cambios
                            ];


                        } else {
                            if ($db_accion == 1) {
                                //evaluamos entrada
                                if ($cantidad > $db_cantidad) {
                                    $diferencia = $cantidad - $db_cantidad;
                                    //incremento la entrada
                                    $procesar_detalles[$i] = true;
                                    $db_disponible = $db_disponible + $diferencia;
                                } else {
                                    $diferencia = $db_cantidad - $cantidad;
                                    //verifico el stock antes de reducir entrada
                                    if ($db_disponible >= $diferencia) {
                                        //redusco la entrada
                                        $procesar_detalles[$i] = true;
                                        $db_disponible = $db_disponible - $diferencia;
                                    } else {
                                        $error[$i] = true;
                                        $html .= '<span class="text-sm">Para4 <strong> - ' . formatoMillares($diferencia, 3) . '</strong> del articulo <strong>' . $stock->articulo->codigo . '</strong>. el stock actual es <strong>' . formatoMillares($db_disponible, 3) . ' ' . $db_unidad_codigo . '</strong></span><br>';
                                        $this->addError('ajusteCantidad.' . $i, 'error');
                                    }
                                }
                                $revisados[$i] = [
                                    'accion' => 'editar_stock',
                                    'id' => $stock->id,
                                    'actual' => $db_disponible + $db_comprometido,
                                    'disponible' => $db_disponible,
                                ];
                            } else {
                                //evaluamos salida
                                if ($cantidad < $db_cantidad) {
                                    $diferencia = $db_cantidad - $cantidad;
                                    //redusco la salida
                                    $procesar_detalles[$i] = true;
                                    $db_disponible = $db_disponible + $diferencia;
                                } else {
                                    $diferencia = $cantidad - $db_cantidad;
                                    //verifico el stock antes de aumentar la salida
                                    if ($db_disponible >= $diferencia) {
                                        //aumento la salida
                                        $procesar_detalles[$i] = true;
                                        $db_disponible = $db_disponible - $diferencia;
                                    } else {
                                        $error[$i] = true;
                                        $html .= '<span class="text-sm">Para5 <strong> - ' . formatoMillares($diferencia, 3) . '</strong> del articulo <strong>' . $stock->articulo->codigo . '</strong>. el stock actual es <strong>' . formatoMillares($db_disponible, 3) . ' ' . $db_unidad_codigo . '</strong></span><br>';
                                        $this->addError('ajusteCantidad.' . $i, 'error');
                                    }
                                }
                                $revisados[$i] = [
                                    'accion' => 'editar_stock',
                                    'id' => $stock->id,
                                    'actual' => $db_disponible + $db_comprometido,
                                    'disponible' => $db_disponible,
                                ];
                            }
                        }


                    }

                } else {
                    $success[$i] = true;
                }


            } else {
                //nuevo renglon

                $stock = Stock::where('empresas_id', $this->empresas_id)
                    ->where('articulos_id', $articulo_id)
                    ->where('almacenes_id', $almacen_id)
                    ->where('unidades_id', $unidad_id)
                    ->first();

                if ($accion == 1) {
                    //entrada
                    $procesar_detalles[$i] = true;
                    if ($stock) {
                        $db_comprometido = $stock->comprometido;
                        $db_disponible = $stock->disponible;
                        if (!empty($itemEliminados)) {
                            foreach ($itemEliminados as $eliminado) {
                                if ($stock->id == $eliminado['id']) {
                                    if ($eliminado['accion'] == 1) {
                                        //retire la entrada
                                        $db_disponible = $db_disponible - $eliminado['cantidad'];
                                    } else {
                                        //retiro la salida
                                        $db_disponible = $db_disponible + $eliminado['cantidad'];
                                    }
                                }
                            }
                        }
                        $disponible = $db_disponible + $cantidad;
                        $actual = $disponible + $db_comprometido;
                        //edito
                        $revisados[$i] = [
                            'accion' => 'editar_stock',
                            'id' => $stock->id,
                            'actual' => $actual,
                            'disponible' => $disponible,
                            'array' => false
                        ];
                    } else {
                        //nuevo
                        $revisados[$i] = [
                            'accion' => 'nuevo_stock',
                            'articulo_id' => $articulo_id,
                            'almacen_id' => $almacen_id,
                            'unidad_id' => $unidad_id,
                            'actual' => $cantidad,
                            'disponible' => $cantidad,
                            'almacen_pricipal' => $this->ajuste_almacenes_tipo[$i],
                            'array' => false
                        ];
                    }

                } else {
                    //salida
                    if ($stock) {
                        $db_comprometido = $stock->comprometido;
                        $db_disponible = $stock->disponible;
                        if (!empty($itemEliminados)) {
                            foreach ($itemEliminados as $eliminado) {
                                if ($stock->id == $eliminado['id']) {
                                    if ($eliminado['accion'] == 1) {
                                        //retire la entrada
                                        $db_disponible = $db_disponible - $eliminado['cantidad'];
                                    } else {
                                        //retiro la salida
                                        $db_disponible = $db_disponible + $eliminado['cantidad'];
                                    }
                                }
                            }
                        }
                        if ($db_disponible >= $cantidad) {
                            $disponible = $db_disponible - $cantidad;
                            $actual = $disponible + $db_comprometido;
                            $procesar_detalles = true;
                            $revisados[$i] = [
                                'accion' => 'editar_stock',
                                'id' => $stock->id,
                                'actual' => $actual,
                                'disponible' => $disponible,
                                'array' => false
                            ];
                        } else {
                            $error[$i] = true;
                            $html .= 'Para6 <strong>' . formatoMillares($this->ajusteCantidad[$i], 3) . '</strong> del articulo <strong>' . $this->ajusteArticulo[$i] . '</strong>. el stock actual es <strong>' . formatoMillares($db_disponible, 3) . '</strong><br>';
                            $this->addError('ajusteCantidad.' . $i, 'error');
                        }
                    } else {
                        $error[$i] = true;
                        $html .= 'Para7 <strong>' . formatoMillares($this->ajusteCantidad[$i], 3) . '</strong> del articulo <strong>' . $this->ajusteArticulo[$i] . '</strong>. el stock actual es <strong>0,000</strong><br>';
                        $this->addError('ajusteCantidad.' . $i, 'error');
                    }
                }

            }

        }


        if ($procesar_ajuste || (!empty($procesar_detalles) && empty($error)) || (!empty($this->borraritems) && empty($error))) {

            if ($procesar_ajuste) {
                $ajuste->save();
            }

            if (!empty($this->borraritems)) {
                foreach ($this->borraritems as $item) {
                    $detalles = AjusDetalle::find($item['id']);
                    $db_articulo_id = $detalles->articulos_id;
                    $db_almacen_id = $detalles->almacenes_id;
                    $db_unidad_id = $detalles->unidades_id;
                    $db_cantidad = $detalles->cantidad;
                    $db_accion = $detalles->tipo->tipo;
                    //me traigo el stock actual
                    $stock = Stock::where('empresas_id', $this->empresas_id)
                        ->where('articulos_id', $db_articulo_id)
                        ->where('almacenes_id', $db_almacen_id)
                        ->where('unidades_id', $db_unidad_id)
                        ->first();
                    if ($stock) {
                        //bien
                        $db_id = $stock->id;
                        $db_disponible = $stock->disponible;
                        $db_comprometido = $stock->comprometido;

                        if ($db_accion == 1) {
                            //revierto entrada
                            if ($db_disponible >= $db_cantidad) {
                                $disponible = $db_disponible - $db_cantidad;
                                $actual = $disponible + $db_comprometido;
                                $stock = Stock::find($db_id);
                                $stock->actual = $actual;
                                $stock->disponible = $disponible;
                                $stock->save();
                                $detalles->delete();
                            }
                        } else {
                            //revierto salida
                            $disponible = $db_disponible + $db_cantidad;
                            $actual = $disponible + $db_comprometido;
                            $stock = Stock::find($db_id);
                            $stock->actual = $actual;
                            $stock->disponible = $disponible;
                            $stock->save();
                            $detalles->delete();
                        }

                    }
                }
            }

            if (!empty($procesar_detalles)) {

                for ($i = 0; $i < $this->contador; $i++) {
                    if ($this->detalles_id[$i]) {
                        //edito
                        $detalles = AjusDetalle::find($this->detalles_id[$i]);
                    } else {
                        //nuevo
                        $detalles = new AjusDetalle();
                    }
                    $detalles->ajustes_id = $this->ajustes_id;
                    $detalles->tipos_id = $this->ajuste_tipos_id[$i];
                    $detalles->articulos_id = $this->ajuste_articulos_id[$i];
                    $detalles->almacenes_id = $this->ajuste_almacenes_id[$i];
                    $detalles->unidades_id = $this->ajusteUnidad[$i];
                    $detalles->cantidad = $this->ajusteCantidad[$i];
                    $detalles->save();
                }

                foreach ($revisados as $revisado) {
                    if ($revisado['accion'] == 'nuevo_stock') {
                        //nuevo
                        $stock = new Stock();
                        $stock->empresas_id = $this->empresas_id;
                        $stock->articulos_id = $revisado['articulo_id'];
                        $stock->almacenes_id = $revisado['almacen_id'];
                        $stock->unidades_id = $revisado['unidad_id'];
                        $stock->actual = $revisado['actual'];
                        $stock->comprometido = 0;
                        $stock->disponible = $revisado['disponible'];
                        $stock->vendido = 0;
                        $stock->almacen_principal = $revisado['almacen_pricipal'];
                        $stock->save();
                    } else {
                        //edito
                        $stock = Stock::find($revisado['id']);
                        $stock->actual = $revisado['actual'];
                        $stock->disponible = $revisado['disponible'];
                        $stock->save();
                    }
                    if (!empty($revisado['cambios'])) {
                        if ($revisado['cambios']['accion'] == 'nuevo_stock') {
                            //nuevo
                            $stock = new Stock();
                            $stock->empresas_id = $this->empresas_id;
                            $stock->articulos_id = $revisado['cambios']['articulo_id'];
                            $stock->almacenes_id = $revisado['cambios']['almacen_id'];
                            $stock->unidades_id = $revisado['cambios']['unidad_id'];
                            $stock->actual = $revisado['cambios']['actual'];
                            $stock->comprometido = 0;
                            $stock->disponible = $revisado['cambios']['disponible'];
                            $stock->vendido = 0;
                            $stock->almacen_principal = $revisado['cambios']['almacen_pricipal'];
                            $stock->save();
                        } else {
                            //edito
                            $stock = Stock::find($revisado['cambios']['id']);
                            $stock->actual = $revisado['cambios']['actual'];
                            $stock->disponible = $revisado['cambios']['disponible'];
                            $stock->save();
                        }
                    }
                }

            }

            $this->show($this->ajustes_id);
            $this->dispatch('showStock')->to(StockComponent::class);
            $this->alert('success', 'Ajuste Actualizado.');

        } else {

            if (empty($success) || !empty($error)) {
                $this->alert('warning', '¡Stock Insuficiente!', [
                    'position' => 'center',
                    'timer' => '',
                    'toast' => false,
                    'html' => $html,
                    'showConfirmButton' => true,
                    'onConfirmed' => '',
                    'confirmButtonText' => 'OK',
                ]);
            } else {
                $this->alert('info', 'No se realizo ningún cambio.');
                $this->show($this->ajustes_id);
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
            'onConfirmed' => 'confirmedBorrarAjuste',
        ]);
    }

    #[On('confirmedBorrarAjuste')]
    public function confirmedBorrarAjuste()
    {
        $ajuste = Ajuste::find($this->ajustes_id);
        $estatus = $ajuste->estatus;

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

                $listarDetalles = AjusDetalle::where('ajustes_id', $ajuste->id)->get();

                foreach ($listarDetalles as $detalle) {

                    $db_articulo_id = $detalle->articulos_id;
                    $db_almacen_id = $detalle->almacenes_id;
                    $db_unidad_id = $detalle->unidades_id;
                    $db_cantidad = $detalle->cantidad;
                    $db_accion = $detalle->tipo->tipo;

                    $stock = Stock::where('empresas_id', $this->empresas_id)
                        ->where('articulos_id', $db_articulo_id)
                        ->where('almacenes_id', $db_almacen_id)
                        ->where('unidades_id', $db_unidad_id)
                        ->first();

                    if ($stock) {

                        $db_id = $stock->id;
                        $db_disponible = $stock->disponible;
                        $db_comprometido = $stock->comprometido;

                        if ($db_accion == 1) {
                            //revierto entrada
                            if ($db_disponible >= $db_cantidad) {
                                $disponible = $db_disponible - $db_cantidad;
                            } else {
                                $disponible = 0;
                            }
                            $actual = $disponible + $db_comprometido;
                        } else {
                            //revierto salida
                            $disponible = $db_disponible + $db_cantidad;
                            $actual = $disponible + $db_comprometido;
                        }
                        //aplico los cambios
                        $stock = Stock::find($db_id);
                        $stock->actual = $actual;
                        $stock->disponible = $disponible;
                        $stock->save();
                    }

                }

            }

            $ajuste->estatus = 0;
            $ajuste->save();

            if ($this->opcionDestroy == "delete") {
                $ajuste->delete();
                $this->reset('ajustes_id');
                $this->limpiarAjustes();
                $message = "Ajuste Eliminado.";
            } else {
                $this->show($ajuste->id);
                $message = "Ajuste Anulado.";
            }

            $this->dispatch('showStock')->to(StockComponent::class);
            $this->alert(
                'success',
                $message
            );

        }
    }

    #[On('buscar')]
    public function buscar($keyword)
    {
        $this->keyword = $keyword;
        /*$articulos = Articulo::where('codigo', 'LIKE', "%$keyword%")
            ->orWhere('descripcion', 'LIKE', "%$keyword%")->get();
        foreach ($articulos as $articulo){
            $this->keywordStock[] = [
                'id' => $articulo->id
            ];
        }*/
    }

    #[On('showAjustes')]
    public function showAjustes()
    {
        if ($this->ajustes_id) {
            $this->show($this->ajustes_id);
        } else {
            $this->limpiarAjustes();
        }
        $this->reset('keyword');
    }

    protected function getNextCodigo(): array
    {
        $codigo = array();

        $parametro = Parametro::where("nombre", "proximo_codigo_ajustes")->where('tabla_id', $this->empresas_id)->first();
        if ($parametro) {
            $codigo['id'] = $parametro->id;
            $codigo['proximo'] = (int)$parametro->valor;
        }else{
            $codigo['id'] = null;
            $codigo['proximo'] = 1;
        }

        $parametro = Parametro::where("nombre", "formato_codigo_ajustes")->where('tabla_id', $this->empresas_id)->first();
        if ($parametro) {
            $codigo['formato'] = $parametro->valor;
        }else{
            $codigo['formato'] = 'N'.$this->empresas_id.'-';
        }

        $parametro = Parametro::where("nombre", "editable_codigo_ajustes")->where('tabla_id', $this->empresas_id)->first();
        if ($parametro){
            if ($parametro->valor == 1){
                $codigo['editable'] = true;
            }else{
                $codigo['editable'] = false;
            }
        }else{
            $codigo['editable'] = false;
        }

        $parametro = Parametro::where("nombre", "editable_fecha_ajustes")->where('tabla_id', $this->empresas_id)->first();
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
            $parametro->nombre = "proximo_codigo_ajustes";
        }else{
            //edito
            $parametro = Parametro::find($id);
        }
        $parametro->valor = $proximo + 1;
        $parametro->save();
    }

}
