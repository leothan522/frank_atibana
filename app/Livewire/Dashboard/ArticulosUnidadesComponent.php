<?php

namespace App\Livewire\Dashboard;

use App\Models\Articulo;
use App\Models\ArtUnid;
use App\Models\Stock;
use App\Models\Unidad;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class ArticulosUnidadesComponent extends Component
{
    use LivewireAlert;

    public $articulos_id, $primaria = false, $primaria_edit = false, $editar = false;
    public $primaria_code, $primaria_id, $primaria_nombre, $unidades_id;

    public function render()
    {
        $unidades = Unidad::orderBy('codigo', 'ASC')->get();
        $unidades->each(function ($unidad) {
            $unidad->ver = true;
            if ($unidad->id == $this->primaria_id && !$this->editar) {
                $unidad->ver = false;
            }
            $exite = ArtUnid::where('articulos_id', $this->articulos_id)->where('unidades_id', $unidad->id)->first();
            if ($exite) {
                $unidad->ver = false;
            }
        });

        $artUnd = ArtUnid::where('articulos_id', $this->articulos_id)->get();

        return view('livewire.dashboard.articulos-unidades-component')
            ->with('listarUnidades', $unidades)
            ->with('listarUnd', $artUnd)
            ;
    }

    #[On('getArticuloUnidades')]
    public function getArticuloUnidades($articuloID)
    {
        $this->articulos_id = $articuloID;
        $this->limpiarUnidades();
    }

    public function limpiarUnidades()
    {
        $this->resetErrorBag();
        $this->reset([
            'primaria', 'primaria_id', 'primaria_code', 'primaria_nombre', 'unidades_id', 'primaria_edit', 'editar'
        ]);
        $articulo = Articulo::find($this->articulos_id);
        if ($articulo) {
            if ($articulo->unidades_id){
                $this->primaria = true;
                $this->primaria_id = $articulo->unidades_id;
                $this->primaria_code = $articulo->unidad->codigo;
                $this->primaria_nombre = $articulo->unidad->nombre;
                $stock = Stock::where('articulos_id', $this->articulos_id)
                    ->where('unidades_id', $this->primaria_id)
                    ->first();
                if (!$stock){
                    $this->primaria_edit = true;
                }
            }
        }else{
            $this->dispatch('cerrarModalArticulosPropiedades', selector: 'btn_modal_articulos_unidades');
        }
    }

    public function save()
    {
        $rules = ['unidades_id' => 'required'];
        $this->validate($rules);
        $articulo = Articulo::find($this->articulos_id);
        if ($articulo){
            if (!$this->primaria || $this->editar){
                $articulo->unidades_id = $this->unidades_id;
                $articulo->save();
            }else{
                $artUnd = new ArtUnid();
                $artUnd->articulos_id = $this->articulos_id;
                $artUnd->unidades_id = $this->unidades_id;
                $artUnd->save();
            }
            $this->alert('success', 'Unidad Establecida.');
            $this->limpiarUnidades();
        }else{
            $this->dispatch('cerrarModalArticulosPropiedades', selector: 'btn_modal_articulos_unidades');
        }
    }

    public function edit()
    {
        $articulo = Articulo::find($this->articulos_id);
        if ($articulo){
            $this->unidades_id = $this->primaria_id;
            $this->editar = true;
            $this->dispatch('setUnd', id: $this->unidades_id);
        }else{
            $this->dispatch('cerrarModalArticulosPropiedades', selector: 'btn_modal_articulos_unidades');
        }
    }

    public function destroy($id)
    {
        $artund = ArtUnid::find($id);

        //codigo para verificar si realmente se puede borrar, dejar false si no se requiere validacion
        $vinculado = false;

        if ($artund){
            $articulo_id = $artund->articulos_id;
            $unidad_id = $artund->unidades_id;
            $stock = Stock::where('articulos_id', $articulo_id)->where('unidades_id', $unidad_id)->first();
            if ($stock){
                $vinculado = true;
            }
        }

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
            if ($artund){
                $artund->delete();
                $this->alert('success', 'Unidad Eliminada.');
            }
        }
        $this->limpiarUnidades();
    }

    #[On('setUnd')]
    public function setUnd($id)
    {
        //JS
    }


}
