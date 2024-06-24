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
            'listarDetalles', 'verSegmento'
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
        $this->validate();

        if (empty($this->codigo)) {
            $this->codigo = $this->proximo_codigo['formato'] . cerosIzquierda($this->proximo_codigo['proximo'], numSizeCodigo());
        }

        if (empty($this->ajuste_fecha)) {
            $this->fecha = date("Y-m-d H:i");
        }

        $procesar = true;
        $html = null;

        //para validar stock
        $listarArticulos = [];
        for ($i = 0; $i < $this->contador; $i++) {
            //recetas
            $idReceta = $this->idReceta[$i];
            $cantidadReceta = $this->cantidad[$i];
            $almacenes_id = $this->idAlmacen[$i];
            //articulos
            $detallesReceta = ReceDetalle::where('recetas_id', $idReceta)->get();
            foreach ($detallesReceta as $articulo) {
                $idArticulo = $articulo->articulos_id;
                $idUnidad = $articulo->unidades_id;
                $cantidadArticulo = $articulo->cantidad;

                $buscar = array_key_exists(mb_strtoupper($articulo->articulo->codigo), $listarArticulos);

                if (!$buscar) {
                    $listarArticulos['' . mb_strtoupper($articulo->articulo->codigo) . ''] = [
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
                    $cantidad = $listarArticulos['' . mb_strtoupper($articulo->articulo->codigo) . '']['cantidad'];
                    $listarArticulos['' . mb_strtoupper($articulo->articulo->codigo) . '']['cantidad'] = $cantidad + ($cantidadReceta * $cantidadArticulo);
                }
            }
        }

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
                    $html .= 'Para <strong>' . formatoMillares($articulo['cantidad'], 3) . ' '.$articulo['unidad'].' </strong> del articulo <strong>' . $articulo['codigo'] . '</strong>. El stock es <strong>' . formatoMillares($disponible, 3) . '</strong><br>';
                    $this->addError('cantidad.' . $articulo['i'], 'error');
                }
            } else {
                $procesar = false;
                $html .= 'Para <strong>' . formatoMillares($articulo['cantidad'], 3) . '</strong> del articulo <strong>' . $articulo['codigo'] . '</strong>. El stock es <strong>0,000</strong><br>';
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
        $this->contador = DespDetalle::where('despachos_id', $this->despachos_id)->count();
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

    protected function getNextCodigo()
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

        $parametro = Parametro::where("nombre", "formato_codigo_despahos")->where('tabla_id', $this->empresas_id)->first();
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

}
