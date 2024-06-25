<?php

namespace App\Livewire\Dashboard;

use App\Models\AjusDetalle;
use App\Models\Ajuste;
use App\Models\AjusTipo;
use App\Models\Almacen;
use App\Models\Articulo;
use App\Models\Despacho;
use App\Models\DespDetalle;
use App\Models\Empresa;
use App\Models\Parametro;
use App\Models\ReceDetalle;
use App\Models\Stock;
use App\Models\Unidad;
use Carbon\Carbon;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class StockComponent extends Component
{
    use LivewireAlert;

    public $rows = 0, $numero = 14, $empresas_id, $tableStyle = false;
    public $empresa, $viewMovimientos = false;
    public $modalEmpresa, $modalArticulo, $modalStock, $modalUnidad;
    //public $modulo = 'stock';

    public $rowsMovimientos = 0, $listarMovimientos;
    public $almacenes_id, $almacen;

    public function mount()
    {
        $this->setLimit();
    }

    public function render()
    {
        $this->empresa = Empresa::find($this->empresas_id);
        //stock
        $stockAlmacenes = Almacen::where('empresas_id', $this->empresas_id)->get();
        $stockAlmacenes->each(function ($almacen){
            $stock = Stock::where('empresas_id', $this->empresas_id)
                ->where('almacenes_id', $almacen->id)
                ->orderBy('actual', 'DESC')
                ->limit($this->rows)
                ->get();
            $almacen->stock = $stock;
            $rows = Stock::where('empresas_id', $this->empresas_id)
                ->where('almacenes_id', $almacen->id)
                ->orderBy('actual', 'DESC')
                ->count();
            $almacen->rows = $rows;
        });

        return view('livewire.dashboard.stock-component')
            ->with('stockAlmacenes', $stockAlmacenes);
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

    #[On('getEmpresaStock')]
    public function getEmpresaStock($empresaID)
    {
        $this->empresas_id = $empresaID;
        $this->limpiarStock();
    }

    public function limpiarStock()
    {
        $this->reset([
            'empresa', 'viewMovimientos',
            'modalEmpresa', 'modalArticulo', 'modalStock', 'modalUnidad',
            'rowsMovimientos', 'listarMovimientos',
            'almacenes_id', 'almacen'
        ]);
    }

    public function verArticulo($id, $unidad)
    {
        $this->modalEmpresa = $this->empresa;
        $this->modalArticulo = Articulo::find($id);
        $this->modalUnidad = Unidad::find($unidad);
        $this->modalStock = Stock::where('empresas_id', $this->empresas_id)
            ->where('articulos_id', $id)
            ->where('unidades_id', $unidad)
            ->get();
    }

    public function verMovimientos($id)
    {
        $this->almacenes_id = $id;
        $this->almacen = Almacen::find($this->almacenes_id);

        $ajustes = Ajuste::where('empresas_id', $this->empresas_id)
            ->where('estatus', 1)
            ->orderBy('created_at', 'DESC')
            ->limit($this->rows)
            ->get();
        $i = 0;
        $listarMovimientos = [];
        foreach ($ajustes as $ajuste){

            $ajustes_id = $ajuste->id;
            $code = $ajuste->codigo;
            $fecha = Carbon::parse($ajuste->created_at)->format('Y-m-d H:i:s');;
            $segmento = null;
            if ($ajuste->segmentos_id){
                $segmento = $ajuste->segmentos->descripcion;
            }

            $detalles = AjusDetalle::where('ajustes_id', $ajuste->id)->where('almacenes_id', $this->almacenes_id)->get();
            $y = 0;
            $listarDetalles = [];
            foreach ($detalles as $detalle){
                $tipo = $detalle->tipo->codigo;
                $articulos_id = $detalle->articulos_id;
                $codigo = $detalle->articulo->codigo;
                $articulo = $detalle->articulo->descripcion;
                $unidades_id = $detalle->unidades_id;
                $unidad = $detalle->unidad->codigo;
                $cantidad = $detalle->cantidad;
                if ($detalle->tipo->tipo == 1){
                    $entrada = true;
                }else{
                    $entrada = false;
                }
                $listarDetalles[$y] = [
                    'tipo' => $tipo,
                    'codigo' => $codigo,
                    'articulo' => $articulo,
                    'unidad' => $unidad,
                    'cantidad' => $cantidad,
                    'entrada' => $entrada,
                    'articulos_id' => $articulos_id,
                    'almacenes_id' => $this->almacenes_id,
                    'unidades_id' => $unidades_id
                ];
                $y++;
                $this->rowsMovimientos++;
            }

            $listarMovimientos[$i] = [
                'tabla' => 'ajustes',
                'id' => $ajustes_id,
                'codigo' => $code,
                'fecha' => $fecha,
                'segmento' => $segmento,
                'detalles' => $listarDetalles
            ];
            $i++;
        }

        $arrayAjustes = $listarMovimientos;

        $despachos = Despacho::where('empresas_id', $this->empresas_id)
            ->where('estatus', 1)
            ->orderBy('created_at', 'DESC')
            ->limit($this->rows)
            ->get();

        $i = 0;
        $listarMovimientos = [];
        foreach ($despachos as $despacho){

            $despachos_id = $despacho->id;
            $code = $despacho->codigo;
            $fecha = Carbon::parse($despacho->created_at)->format('Y-m-d H:i:s');
            $segmento = null;
            if ($despacho->segmentos_id){
                $segmento = $despacho->segmentos->descripcion;
            }

            $detalles = DespDetalle::where('despachos_id', $despacho->id)->where('almacenes_id', $this->almacenes_id)->get();

            foreach ($detalles as $detalle){
                $getTipo = AjusTipo::where('tipo', 2)->first();
                $tipo = $getTipo->codigo;

                $recetas = ReceDetalle::where('recetas_id', $detalle->recetas_id)->get();
                $y = 0;
                $listarDetalles = [];
                foreach ($recetas as $receta){
                    $articulos_id = $receta->articulos_id;
                    $codigo = $receta->articulo->codigo;
                    $articulo = $receta->articulo->descripcion;
                    $unidades_id = $receta->unidades_id;
                    $unidad = $receta->unidad->codigo;
                    $cantidad = $detalle->cantidad * $receta->cantidad;
                    $listarDetalles[$y] = [
                        'tipo' => $tipo,
                        'codigo' => $codigo,
                        'articulo' => $articulo,
                        'unidad' => $unidad,
                        'cantidad' => $cantidad,
                        'entrada' => false,
                        'articulos_id' => $articulos_id,
                        'almacenes_id' => $this->almacenes_id,
                        'unidades_id' => $unidades_id
                    ];
                    $y++;
                    $this->rowsMovimientos++;
                }
            }

            $listarMovimientos[$i] = [
                'tabla' => 'despachos',
                'id' => $despachos_id,
                'codigo' => $code,
                'fecha' => $fecha,
                'segmento' => $segmento,
                'detalles' => $listarDetalles
            ];
            $i++;
        }

        $arrayDespachos = $listarMovimientos;

        $arrayCombinados = array_merge($arrayAjustes, $arrayDespachos);

        $this->listarMovimientos = collect($arrayCombinados)->sortByDesc('fecha');

        //dd($listarMovimientos);

        /*$stock = Stock::where('empresas_id', $this->empresas_id)
                    ->where('articulos_id', $detalle->articulos_id)
                    ->where('almacenes_id', $detalle->almacenes_id)
                    ->where('unidades_id', $detalle->unidades_id)
                    ->first();
                if ($stock){
                    $this->getSaldo = $stock->actual;
                }else{
                    $this->getSaldo = 0;
                }*/

        //dd($this->rowsMovimientos);

        if ($this->rowsMovimientos > $this->numero) {
            $this->tableStyle = true;
        }
        $this->viewMovimientos = true;
    }

    public function irAjuste($id, $codigo)
    {
        $this->dispatch('show', id: $id)->to(AjustesComponent::class);
        $this->dispatch('buscar', keyword: $codigo)->to(AjustesComponent::class);
    }

    #[On('showStock')]
    public function showStock()
    {
        if ($this->almacenes_id){
            $this->verMovimientos($this->almacenes_id);
        }
    }

}
