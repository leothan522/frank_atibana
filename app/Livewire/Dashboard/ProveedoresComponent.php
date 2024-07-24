<?php

namespace App\Livewire\Dashboard;

use App\Models\ArtProv;
use App\Models\Proveedor;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProveedoresComponent extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $rows = 0, $numero = 14, $tableStyle = false;
    public $view, $nuevo = true, $cancelar = false, $footer = false, $edit = false, $new_proveedor = false, $keyword;
    public $photo, $verImagen, $img_borrar_principal, $img_principal;
    public $proveedores_id, $rif, $nombre, $telefono, $direccion, $banco, $cuenta, $estatus;
    public $listarVinculados, $btnVinculados;

    public function mount()
    {
        $this->setLimit();
        $proveedor = Proveedor::orderBy('created_at', 'DESC')->first();
        if ($proveedor){
            $this->show($proveedor->id);
        }
    }

    public function render()
    {
        $proveedor = Proveedor::buscar($this->keyword)
            ->orderBy('rif', 'ASC')
            ->limit($this->rows)
            ->get();
        $rowsProveedor = Proveedor::count();

        if ($rowsProveedor > $this->numero) {
            $this->tableStyle = true;
        }

        return view('livewire.dashboard.proveedores-component')
            ->with('listarProveedores', $proveedor)
            ->with('rowsProveedores', $rowsProveedor)
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

    public function limpiar()
    {
        $this->resetErrorBag();
        $this->reset([
            'view', 'nuevo', 'cancelar', 'footer', 'new_proveedor',
            'photo', 'verImagen', 'img_borrar_principal', 'img_principal',
            'rif', 'nombre', 'telefono', 'direccion', 'banco', 'cuenta', 'estatus'
        ]);
    }

    public function create()
    {
        $this->limpiar();
        $this->new_proveedor = true;
        $this->view = "form";
        $this->nuevo = false;
        $this->cancelar = true;
        $this->edit = false;
        $this->footer = false;
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:1024', // 1MB Max
        ]);
        if ($this->img_principal){
            $this->img_borrar_principal = $this->img_principal;
        }
    }

    public function rules()
    {
        return [
            'rif'       =>  ['required', 'min:6', Rule::unique('proveedores')->ignore($this->proveedores_id)],
            'nombre'    =>  'required|min:4',
            'telefono' =>  'required',
            'direccion' =>  'required',
            'banco'      =>  'nullable',
            'cuenta'    =>  'nullable',
            'photo'     =>  'image|max:1024|nullable'
        ];
    }

    public function save()
    {
        $this->validate();
        $imagen = null;
        if ($this->proveedores_id && !$this->new_proveedor){
            //editar
            $proveedor = Proveedor::find($this->proveedores_id);
            if ($proveedor){
                $imagen = $proveedor->imagen;
            }
            $message = "Proveedor Actualizado.";
        }else{
            //nuevo
            $proveedor = new Proveedor();
            $message = "Proveedor Creado.";
        }

        if ($proveedor){
            $proveedor->rif = $this->rif;
            $proveedor->nombre = $this->nombre;
            $proveedor->telefono = $this->telefono;
            $proveedor->direccion = $this->direccion;
            $proveedor->banco = $this->banco;
            $proveedor->cuenta = $this->cuenta;

            if ($this->photo){
                $ruta = $this->photo->store('public/proveedores');
                $proveedor->imagen = str_replace('public/', 'storage/', $ruta);
                //miniaturas
                $nombre = explode('proveedores/', $proveedor->imagen);
                $path_data = "storage/proveedores/size_".$nombre[1];
                $miniatura = crearMiniaturas($proveedor->imagen, $path_data);
                $proveedor->mini = $miniatura['mini'];
                //borramos imagenes anteriones si existen
                if ($this->img_borrar_principal){
                    borrarImagenes($imagen, 'proveedores');
                }
            }else{
                if ($this->img_borrar_principal){
                    $proveedor->imagen = null;
                    $proveedor->mini = null;
                    $proveedor->detail = null;
                    $proveedor->cart = null;
                    $proveedor->banner = null;
                    borrarImagenes($this->img_borrar_principal, 'proveedores');
                }
            }

            $proveedor->save();
            $this->show($proveedor->id);
            $this->alert('success', $message);
        }else{
            $this->limpiar();
        }

    }

    #[On('show')]
    public function show($id)
    {
        $this->limpiar();
        $proveedor = Proveedor::find($id);
        if ($proveedor){
            $this->edit = true;
            $this->view = "show";
            $this->footer = true;
            $this->proveedores_id = $proveedor->id;
            $this->rif = $proveedor->rif;
            $this->nombre = $proveedor->nombre;
            $this->telefono = $proveedor->telefono;
            $this->direccion = $proveedor->direccion;
            $this->banco = $proveedor->banco;
            $this->cuenta = $proveedor->cuenta;
            $this->estatus = $proveedor->estatus;
            $this->verImagen = $proveedor->mini;
            $this->img_principal = $proveedor->imagen;

            $this->btnVinculados = ArtProv::where('proveedores_id', $this->proveedores_id)->first();
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
        $proveedor = Proveedor::find($this->proveedores_id);

        //codigo para verificar si realmente se puede borrar, dejar false si no se requiere validacion
        $vinculado = false;

        $articulo = ArtProv::where('proveedores_id', $this->proveedores_id)->first();
        if ($articulo){
            $vinculado = true;
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
            if ($proveedor){
                $imagen = $proveedor->imagen;
                $proveedor->delete();
                borrarImagenes($imagen, 'proveedores');
                $this->edit = false;
                $this->reset('proveedores_id');
                $this->alert('success', 'Proveedor Eliminado.');
            }
            $this->limpiar();
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
        if ($this->proveedores_id) {
            $this->show($this->proveedores_id);
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

    public function btnActivoInactivo()
    {
        $proveedor = Proveedor::find($this->proveedores_id);
        if ($proveedor){
            if ($this->estatus){
                $proveedor->estatus = 0;
                $this->estatus = 0;
                $message = "Proveedor Inactivo.";
            }else{
                $proveedor->estatus = 1;
                $this->estatus = 1;
                $message = "Proveedor Activo.";
            }
            $proveedor->update();

            $articulos = ArtProv::where('proveedores_id', $proveedor->id)->get();
            foreach ($articulos as $articulo){
                $vinculado = ArtProv::find($articulo->id);
                $vinculado->estatus = $this->estatus;
                $vinculado->save();
            }

            $this->alert('success', $message);
        }else{
            $this->limpiar();
        }
    }

    public function btnBorrarImagen()
    {
        $this->verImagen = null;
        $this->reset('photo');
        $this->img_borrar_principal = $this->img_principal;
    }

    public function btnArticulos()
    {
        $this->listarVinculados = ArtProv::where('proveedores_id', $this->proveedores_id)->get();
    }

    public function desvincular()
    {
        $this->confirm('¿Estas seguro?', [
            'toast' => false,
            'position' => 'center',
            'showConfirmButton' => true,
            'confirmButtonText' => '¡Sí, bórralo!',
            'text' => '¡No podrás revertir esto!',
            'cancelButtonText' => 'No',
            'onConfirmed' => 'confirmedDesvincular',
        ]);
    }

    #[On('confirmedDesvincular')]
    public function confirmedDesvincular()
    {
        $articulos = ArtProv::where('proveedores_id', $this->proveedores_id)->get();
        foreach ($articulos as $articulo){
            $vinculado = ArtProv::find($articulo->id);
            if ($vinculado){
                $vinculado->delete();
            }
        }
        $this->show($this->proveedores_id);
        $this->dispatch('cerrarModal');
    }

    public function actualizar()
    {
        $this->reset(['proveedores_id', 'edit']);
        $this->limpiar();
    }

    #[On('cerrarModal')]
    public function cerrarModal()
    {
        //JS
    }

}
