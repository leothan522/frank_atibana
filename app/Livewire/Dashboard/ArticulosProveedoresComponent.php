<?php

namespace App\Livewire\Dashboard;

use App\Models\ArtProv;
use App\Models\Proveedor;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class ArticulosProveedoresComponent extends Component
{
    use LivewireAlert;

    public $listarProveedores = [];
    public $articulos_id, $proveedores_id, $favorito;

    public function render()
    {

        $proveedores = ArtProv::where('articulos_id', $this->articulos_id)
            ->where('estatus', 1)
            ->orderBy('created_at', 'ASC')->get();

        return view('livewire.dashboard.articulos-proveedores-component')
            ->with('listarArticulosProveedores', $proveedores);
    }

    #[On('getArticuloProveedores')]
    public function getArticuloProveedores($articuloID)
    {
        $this->resetErrorBag();
        $this->reset(['listarProveedores', 'articulos_id', 'proveedores_id', 'favorito']);
        $this->articulos_id = $articuloID;
        $this->listarProveedores = Proveedor::where('estatus', 1)->orderBy('nombre', 'ASC')->get();
        $this->listarProveedores->each(function ($proveedor){
            $proveedor->ver = true;
            $exite = ArtProv::where('articulos_id', $this->articulos_id)->where('proveedores_id', $proveedor->id)->first();
            if ($exite){
                $proveedor->ver = false;
            }
        });
    }

    public function save()
    {
        $rules = [
            'proveedores_id' => 'required'
        ];

        $this->validate($rules);
        $proveedor = new ArtProv();
        $proveedor->articulos_id = $this->articulos_id;
        $proveedor->proveedores_id = $this->proveedores_id;
        $proveedor->save();
        $this->getArticuloProveedores($this->articulos_id);
    }

    public function destroy($id)
    {
        $proveedor = ArtProv::find($id);
        $proveedor->delete();
        $this->getArticuloProveedores($this->articulos_id);
    }



}
