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
    public $contador = 1, $codigoTipo = [], $classTipo = [], $tipos_id = [], $tipos_tipo = [],
        $codigoArticulo = [], $classArticulo = [], $descripcionArticulo = [], $articulos_id = [], $unidades_id = [],  $selectUnidad = [],
        $codigoAlmacen = [], $classAlmacen = [], $almacenes_id = [], $almacenes_tipo = [],
        $cantidad = [], $detalles_id = [];
    public $keywordArticulos, $item, $listarArticulos, $borraritems = [];
    public $proximo_codigo;

    public function mount()
    {
        $this->setLimit();
    }

    public function render()
    {
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
            'codigoTipo', 'classTipo', 'codigoArticulo', 'classArticulo', 'descripcionArticulo', 'unidades_id',
            'selectUnidad', 'codigoAlmacen', 'cantidad', 'listarArticulos', 'keywordArticulos', 'item',
            'tipos_id', 'articulos_id', 'almacenes_id', 'almacenes_tipo',
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

        $this->codigoTipo[0] = null;
        $this->classTipo[0] = null;
        $this->codigoArticulo[0] = null;
        $this->classArticulo[0] = null;
        $this->descripcionArticulo[0] = null;
        $this->selectUnidad[0] = array();
        $this->unidades_id[0] = null;
        $this->codigoAlmacen[0] = null;
        $this->classAlmacen[0] = null;
        $this->cantidad[0] = null;
        $this->detalles_id[0] = null;
    }

    protected function rules()
    {
        return [
            'codigo' => ['nullable', 'min:4', 'alpha_dash:ascii', Rule::unique('ajustes', 'codigo')->ignore($this->ajustes_id)],
            'fecha' => 'nullable',
            'descripcion' => 'required|min:4',
            /*'segmentos_id' => 'required',*/
            'codigoTipo.*' => ['required', Rule::exists('ajustes_tipos', 'codigo')],
            'codigoArticulo.*' => ['required', Rule::exists('articulos', 'codigo')],
            'unidades_id.*' => 'required',
            'codigoAlmacen.*' => ['required', Rule::exists('almacenes', 'codigo')],
            'cantidad.*' => 'required'
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
            if ($this->tipos_tipo[$i] == 2) {
                $stock = Stock::where('empresas_id', $this->empresas_id)
                    ->where('articulos_id', $this->articulos_id[$i])
                    ->where('almacenes_id', $this->almacenes_id[$i])
                    ->where('unidades_id', $this->unidades_id[$i])
                    ->first();
                if ($stock) {
                    $disponible = $stock->disponible;
                    if ($this->cantidad[$i] > $disponible) {
                        $procesar = false;
                        $html .= 'Para <strong>' . formatoMillares($this->cantidad[$i], 3) . '</strong> del articulo <strong>' . $this->codigoArticulo[$i] . '</strong>. el stock es <strong>' . formatoMillares($disponible, 3) . '</strong><br>';
                        $this->addError('cantidad.' . $i, 'error');
                    }
                } else {
                    $procesar = false;
                    $html .= 'Para <strong>' . formatoMillares($this->cantidad[$i], 3) . '</strong> del articulo <strong>' . $this->codigoArticulo[$i] . '</strong>. el stock es <strong>0,000</strong><br>';
                    $this->addError('cantidad.' . $i, 'error');
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
                $detalles->tipos_id = $this->tipos_id[$i];
                $detalles->articulos_id = $this->articulos_id[$i];
                $detalles->almacenes_id = $this->almacenes_id[$i];
                $detalles->unidades_id = $this->unidades_id[$i];
                $detalles->cantidad = $this->cantidad[$i];
                $detalles->save();
                $exite = Stock::where('empresas_id', $this->empresas_id)
                    ->where('articulos_id', $this->articulos_id[$i])
                    ->where('almacenes_id', $this->almacenes_id[$i])
                    ->where('unidades_id', $this->unidades_id[$i])
                    ->first();
                if ($exite) {
                    //edito
                    $stock = Stock::find($exite->id);
                    $compometido = $stock->comprometido;
                    $disponible = $stock->disponible;
                    if ($this->tipos_tipo[$i] == 1) {
                        //sumo entrada
                        $stock->disponible = $disponible + $this->cantidad[$i];
                    } else {
                        //resto salida
                        $stock->disponible = $disponible - $this->cantidad[$i];
                    }
                    $stock->actual = $compometido + $stock->disponible;
                    $stock->save();
                } else {
                    //nuevo
                    $stock = new Stock();
                    $stock->empresas_id = $this->empresas_id;
                    $stock->articulos_id = $this->articulos_id[$i];
                    $stock->almacenes_id = $this->almacenes_id[$i];
                    $stock->unidades_id = $this->unidades_id[$i];
                    $stock->actual = $this->cantidad[$i];
                    $stock->comprometido = 0;
                    $stock->disponible = $this->cantidad[$i];
                    $stock->vendido = 0;
                    $stock->almacen_principal = $this->almacenes_tipo[$i];
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

    #[On('show')]
    public function show($id)
    {
        $this->limpiarAjustes();
        $ajuste = Ajuste::find($id);
        if ($ajuste){
            $this->ajustes_id = $ajuste->id;
            $this->btn_editar = true;
            $this->view = 'show';
            $this->footer = true;

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
                $this->codigoTipo[$i] = $detalle->tipo->codigo;
                $this->tipos_id[$i] = $detalle->tipo->id;
                $this->tipos_tipo[$i] = $detalle->tipo->tipo;
                $this->classTipo[$i] = null;
                $this->codigoArticulo[$i] = $detalle->articulo->codigo;
                $this->articulos_id[$i] = $detalle->articulos_id;
                $this->classArticulo[$i] = null;
                $this->descripcionArticulo[$i] = $detalle->articulo->descripcion;
                $this->selectUnidad[$i] = $array;
                $this->unidades_id[$i] = $detalle->unidades_id;
                $this->codigoAlmacen[$i] = $detalle->almacen->codigo;
                $this->almacenes_id[$i] = $detalle->almacenes_id;
                $this->almacenes_tipo[$i] = $detalle->almacen->tipo;
                $this->classAlmacen[$i] = null;
                $this->cantidad[$i] = $detalle->cantidad;
                $this->detalles_id[$i] = $detalle->id;
                $i++;
            }

        }else{
            $this->limpiarAjustes();
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

        if ($ajuste){

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
                        //$this->addError('cantidad.' . $i, 'error');
                    }
                }
            }


            for ($i = 0; $i < $this->contador; $i++) {

                $detalle_id = $this->detalles_id[$i];
                $tipo_id = $this->tipos_id[$i];
                $accion = $this->tipos_tipo[$i];
                $articulo_id = $this->articulos_id[$i];
                $almacen_id = $this->almacenes_id[$i];
                $unidad_id = $this->unidades_id[$i];
                $cantidad = $this->cantidad[$i];

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
                                        $html .= '<span class="text-sm">Para <strong> - ' . formatoMillares($this->cantidad[$i], 3) . '</strong> del articulo <strong>' . $stock->articulo->codigo . '</strong>. el stock actual es <strong>' . formatoMillares($db_disponible, 3) . ' ' . $db_unidad_codigo . '</strong></span><br>';
                                        $this->addError('cantidad.' . $i, 'error');
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
                                            'almacen_pricipal' => $this->almacenes_tipo[$i]
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
                                            $html .= 'Para <strong> - ' . formatoMillares($this->cantidad[$i], 3) . '</strong> del articulo <strong>' . $this->codigoArticulo[$i] . '</strong>. el stock actual es <strong>' . formatoMillares($db_disponible, 3) . '</strong><br>';
                                            $this->addError('cantidad.' . $i, 'error');
                                        }
                                    } else {
                                        $error[$i] = true;
                                        $html .= 'Para <strong> - ' . formatoMillares($this->cantidad[$i], 3) . '</strong> del articulo <strong>' . $this->codigoArticulo[$i] . '</strong>. el stock actual es <strong>0,000</strong><br>';
                                        $this->addError('cantidad.' . $i, 'error');
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
                                            $html .= '<span class="text-sm">Para <strong> - ' . formatoMillares($diferencia, 3) . '</strong> del articulo <strong>' . $stock->articulo->codigo . '</strong>. el stock actual es <strong>' . formatoMillares($db_disponible, 3) . ' ' . $db_unidad_codigo . '</strong></span><br>';
                                            $this->addError('cantidad.' . $i, 'error');
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
                                            $html .= '<span class="text-sm">Para <strong> - ' . formatoMillares($diferencia, 3) . '</strong> del articulo <strong>' . $stock->articulo->codigo . '</strong>. el stock actual es <strong>' . formatoMillares($db_disponible, 3) . ' ' . $db_unidad_codigo . '</strong></span><br>';
                                            $this->addError('cantidad.' . $i, 'error');
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
                                'almacen_pricipal' => $this->almacenes_tipo[$i],
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
                                $html .= 'Para <strong>' . formatoMillares($this->cantidad[$i], 3) . '</strong> del articulo <strong>' . $this->codigoArticulo[$i] . '</strong>. el stock actual es <strong>' . formatoMillares($db_disponible, 3) . '</strong><br>';
                                $this->addError('cantidad.' . $i, 'error');
                            }
                        } else {
                            $error[$i] = true;
                            $html .= 'Para <strong>' . formatoMillares($this->cantidad[$i], 3) . '</strong> del articulo <strong>' . $this->codigoArticulo[$i] . '</strong>. el stock actual es <strong>0,000</strong><br>';
                            $this->addError('cantidad.' . $i, 'error');
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
                        $detalles->tipos_id = $this->tipos_id[$i];
                        $detalles->articulos_id = $this->articulos_id[$i];
                        $detalles->almacenes_id = $this->almacenes_id[$i];
                        $detalles->unidades_id = $this->unidades_id[$i];
                        $detalles->cantidad = $this->cantidad[$i];
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



        }else{
            $this->limpiarAjustes();
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

            $procesar = true;

            if ($ajuste){

                $estatus = $ajuste->estatus;

                if ($estatus){

                    $listarDetalles = AjusDetalle::where('ajustes_id', $ajuste->id)->get();
                    $html = null;
                    //valido el stock
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

                            $db_disponible = $stock->disponible;
                            $codigo = mb_strtoupper($stock->articulo->codigo);
                            $unidad = mb_strtoupper($stock->unidad->codigo);

                            if ($db_accion == 1) {
                                //revierto entrada
                                if ($db_cantidad > $db_disponible) {
                                    $procesar = false;
                                    $html .= '<span class="text-sm">Para <strong>'.formatoMillares($db_cantidad, 3).' '.$unidad.'</strong> del articulo <strong>'.$codigo.'</strong>, el stock es <strong>'.formatoMillares($db_disponible, 3).' '.$unidad.'</strong><span class="text-sm"><br>';
                                    //$this->addError('cantidad.' . $i, 'error');
                                }
                            }

                        }else{
                            $procesar = false;
                        }

                    }

                    if (!$procesar){
                        //error
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

                if ($procesar){

                    if ($estatus){
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
                                        $actual = $disponible + $db_comprometido;
                                    }
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

                    if ($this->opcionDestroy == "delete") {
                        $ajuste->codigo = "*". $ajuste->codigo;
                        $ajuste->save();
                        $ajuste->delete();
                        $this->reset('ajustes_id');
                        $this->limpiarAjustes();
                        $this->alert('success', "Ajuste Eliminado.");
                    } else {
                        $ajuste->save();
                        $this->show($ajuste->id);
                        $this->alert('success', "Ajuste Anulado.");
                    }

                    $this->dispatch('showStock')->to(StockComponent::class);

                }

            }else{
                $this->limpiarAjustes();
            }

        }
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
            $this->codigoTipo[$this->contador] = null;
            $this->tipos_tipo[$this->contador] = null;
            $this->classTipo[$this->contador] = null;
            $this->codigoArticulo[$this->contador] = null;
            $this->articulos_id[$this->contador] = null;
            $this->classArticulo[$this->contador] = null;
            $this->descripcionArticulo[$this->contador] = null;
            $this->selectUnidad[$this->contador] = array();
            $this->unidades_id[$this->contador] = null;
            $this->codigoAlmacen[$this->contador] = null;
            $this->almacenes_id[$this->contador] = null;
            $this->classAlmacen[$this->contador] = null;
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
                $this->codigoTipo[$i] = $this->codigoTipo[$i + 1];
                $this->tipos_tipo[$i] = $this->tipos_tipo[$i + 1];
                $this->classTipo[$i] = $this->classTipo[$i + 1];
                $this->codigoArticulo[$i] = $this->codigoArticulo[$i + 1];
                $this->articulos_id[$i] = $this->articulos_id[$i + 1];
                $this->classArticulo[$i] = $this->classArticulo[$i + 1];
                $this->descripcionArticulo[$i] = $this->descripcionArticulo[$i + 1];
                $this->selectUnidad[$i] = $this->selectUnidad[$i + 1];
                $this->unidades_id[$i] = $this->unidades_id[$i + 1];
                $this->codigoAlmacen[$i] = $this->codigoAlmacen[$i + 1];
                $this->almacenes_id[$i] = $this->almacenes_id[$i + 1];
                $this->classAlmacen[$i] = $this->classAlmacen[$i + 1];
                $this->cantidad[$i] = $this->cantidad[$i + 1];
                $this->detalles_id[$i] = $this->detalles_id[$i + 1];
            }
            $this->contador--;
            unset($this->codigoTipo[$this->contador]);
            unset($this->classTipo[$this->contador]);
            unset($this->codigoArticulo[$this->contador]);
            unset($this->classArticulo[$this->contador]);
            unset($this->descripcionArticulo[$this->contador]);
            unset($this->selectUnidad[$this->contador]);
            unset($this->unidades_id[$this->contador]);
            unset($this->codigoAlmacen[$this->contador]);
            unset($this->classAlmacen[$this->contador]);
            unset($this->cantidad[$this->contador]);
            unset($this->detalles_id[$this->contador]);
        }
    }

    public function updatedCodigoTipo()
    {
        foreach ($this->codigoTipo as $key => $value) {
            if ($value) {
                $tipo = AjusTipo::where('codigo', $value)->first();
                if ($tipo) {
                    $this->tipos_id[$key] = $tipo->id;
                    $this->tipos_tipo[$key] = $tipo->tipo;
                    $this->classTipo[$key] = "is-valid";
                    $this->resetErrorBag('codigoTipo.' . $key);
                } else {
                    $this->classTipo[$key] = "is-invalid";
                    $this->tipos_id[$key] = null;
                    $this->tipos_tipo[$key] = null;
                }
            }
        }
    }

    public function updatedCodigoArticulo()
    {
        foreach ($this->codigoArticulo as $key => $value) {
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
                    $this->descripcionArticulo[$key] = $articulo->descripcion;
                    $this->selectUnidad[$key] = $array;
                    if (is_null($this->unidades_id[$key])) {
                        $this->unidades_id[$key] = $articulo->unidades_id;
                    }
                    $this->resetErrorBag('codigoArticulo.' . $key);
                    $this->resetErrorBag('unidades_id.' . $key);
                    $this->articulos_id[$key] = $articulo->id;
                    $this->classArticulo[$key] = "is-valid";
                } else {
                    $this->classArticulo[$key] = "is-invalid";
                    $this->descripcionArticulo[$key] = null;
                    $this->articulos_id[$key] = null;
                    $this->selectUnidad[$key] = array();
                    $this->unidades_id[$key] = null;
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
                    $this->almacenes_id[$key] = $almacen->id;
                    $this->almacenes_tipo[$key] = $almacen->tipo;
                    $this->classAlmacen[$key] = "is-valid";
                } else {
                    $this->almacenes_id[$key] = null;
                    $this->almacenes_tipo[$key] = null;
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
        $this->listarArticulos = Articulo::buscar($this->keywordArticulos)
            ->where('empresas_id', $this->empresas_id)
            ->where('estatus', 1)
            ->limit(100)
            ->get();
    }

    public function selectArticulo($codigo)
    {
        $this->codigoArticulo[$this->item] = $codigo;
        $this->updatedCodigoArticulo();
    }

    public function btnEditar()
    {
        $this->view = 'form';
        $this->nuevo = false;
        $this->btn_editar = false;
        $this->btn_cancelar = true;
        $this->footer = false;
        $this->proximo_codigo = $this->getNextCodigo();
    }

    #[On('buscar')]
    public function buscar($keyword)
    {
        $this->keyword = $keyword;
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
