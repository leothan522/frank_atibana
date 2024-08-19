<?php

namespace App\Livewire\Dashboard;

use App\Models\Almacen;
use App\Models\Empresa;
use App\Models\Parametro;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmpresasComponent extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $rows = 0, $numero = 14, $empresas_id, $tableStyle = false;
    public $view = "show", $keyword, $title = "Datos de la Empresa", $btn_cancelar = false, $footer = true, $nuevo = true;
    public $empresa_default, $verDefault, $verImagen, $img_borrar_principal, $img_principal, $verMini;
    public $rif, $nombre, $jefe, $moneda, $telefonos, $email, $direccion, $photo, $default = 0, $permisos;
    public $horario, $horario_id, $lunes, $martes, $miercoles, $jueves, $viernes, $sabado, $domingo, $apertura, $cierre;


    public function mount()
    {
        $this->setLimit();
        $empresas = Empresa::where('default', 1)->first();
        if ($empresas){
            $this->empresa_default = $empresas->id;
            $this->show($this->empresa_default);
        }else{
            $this->create();
            $this->default = 1;
            $this->btn_cancelar = false;
        }
    }

    public function render()
    {
        $tiendas = Empresa::buscar($this->keyword)
            ->orderBy('created_at', 'ASC')
            ->limit($this->rows)
            ->get();
        $rowsTiendas = Empresa::count();
        if ($rowsTiendas > $this->numero) {
            $this->tableStyle = true;
        }
        return view('livewire.dashboard.empresas-component')
            ->with('tiendas', $tiendas);
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
        $this->reset([
            'view', 'title', 'btn_cancelar', 'footer', 'empresas_id', 'verDefault', 'verImagen', 'keyword', 'nuevo',
            'rif', 'nombre', 'jefe', 'moneda', 'telefonos', 'email', 'direccion', 'photo', 'permisos', 'img_borrar_principal',
            'verMini'
        ]);
    }

    public function create()
    {
        $this->limpiar();
        $this->title = "Nueva Empresa";
        $this->btn_cancelar = true;
        $this->view = "form";
        $this->footer = false;
        $this->nuevo = false;

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
            'rif'       =>  ['required', 'min:6', Rule::unique('empresas')->ignore($this->empresas_id)],
            'nombre'    =>  'required|min:4',
            'jefe'      =>  'required|min:4',
            'moneda'    =>  'required',
            'telefonos' =>  'required',
            'email'     =>  'required|email',
            'direccion' =>  'required',
            'photo'     =>  'image|max:1024|nullable'
        ];
    }

    public function save()
    {
        $this->validate();

        if ($this->empresas_id){
            //editar
            $empresa = Empresa::find($this->empresas_id);
            $imagen = $empresa->imagen;
            $message = "Datos Guardados.";
        }else{
            //nuevo
            $empresa = new Empresa();
            $imagen = null;
            $message = "Empresa Creada.";
            $empresa->default = $this->default;
            if ($this->default){
                $this->default = 0;
            }
            $permisos[Auth::id()] = true;
            $permisos = json_encode($permisos);
            $empresa->permisos = $permisos;
        }

        if ($empresa){

            $empresa->rif = $this->rif;
            $empresa->nombre = $this->nombre;
            $empresa->supervisor = $this->jefe;
            $empresa->moneda = $this->moneda;
            $empresa->telefono = $this->telefonos;
            $empresa->email = $this->email;
            $empresa->direccion = $this->direccion;

            if ($this->photo){
                $ruta = $this->photo->store('public/empresas');
                $empresa->imagen = str_replace('public/', 'storage/', $ruta);
                //miniaturas
                $nombre = explode('empresas/', $empresa->imagen);
                $path_data = "storage/empresas/size_".$nombre[1];
                $miniatura = crearMiniaturas($empresa->imagen, $path_data);
                $empresa->mini = $miniatura['mini'];
                /*$empresa->detail = $miniatura['detail'];
                $empresa->cart = $miniatura['cart'];
                $empresa->banner = $miniatura['banner'];*/
                //borramos imagenes anteriones si existen
                if ($this->img_borrar_principal){
                    borrarImagenes($imagen, 'empresas');
                }
            }else{
                if ($this->img_borrar_principal){
                    $empresa->imagen = null;
                    $empresa->mini = null;
                    $empresa->detail = null;
                    $empresa->cart = null;
                    $empresa->banner = null;
                    borrarImagenes($this->img_borrar_principal, 'empresas');
                }
            }

            $empresa->save();

            $this->show($empresa->id);

            $this->alert('success', $message);

        }else{
            $this->show($this->empresa_default);
            $this->dispatch('cerrarModal');
        }
    }

    public function show($id)
    {
        $this->limpiar();
        $empresa = Empresa::find($id);
        if ($empresa){
            $this->empresas_id = $empresa->id;
            $this->nombre = $empresa->nombre;
            $this->rif = $empresa->rif;
            $this->jefe = $empresa->supervisor;
            $this->moneda = $empresa->moneda;
            $this->telefonos = $empresa->telefono;
            $this->email = $empresa->email;
            $this->direccion = $empresa->direccion;
            $this->permisos = $empresa->permisos;
            $this->verDefault = $empresa->default;
            $this->verImagen = $empresa->imagen;
            $this->verMini = $empresa->mini;
            $this->img_principal = $empresa->imagen;
            $this->view = "show";
        }else{
            $this->dispatch('cerrarModal');
        }
    }

    public function edit()
    {
        $this->title = "Editar Empresa";
        $this->btn_cancelar = true;
        $this->view = "form";
    }

    public function convertirDefault($id)
    {
        $empresa = Empresa::find($id);
        if ($empresa){

            $buscar = Empresa::where('default', 1)->first();
            if ($buscar){
                $buscar->default = 0;
                $buscar->update();
            }

            $empresa->default = 1;
            $empresa->update();
            $this->empresa_default = $empresa->id;
            $this->verDefault = $empresa->default;
        }else{
            $this->show($this->empresa_default);
            $this->dispatch('cerrarModal');
        }
    }

    public function destroy($id)
    {
        $this->empresas_id = $id;
        $this->confirm('¿Estas seguro?', [
            'toast' => false,
            'position' => 'center',
            'showConfirmButton' => true,
            'confirmButtonText' =>  '¡Sí, bórralo!',
            'text' =>  '¡No podrás revertir esto!',
            'cancelButtonText' => 'No',
            'onConfirmed' => 'confirmed',
        ]);
    }

    #[On('confirmed')]
    public function confirmed()
    {
        $empresa = Empresa::find($this->empresas_id);
        $imagen = $empresa->imagen;

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
            if ($empresa){
                $empresa->delete();
                borrarImagenes($imagen, 'empresas');
                $this->alert('success', 'Empresa Eliminada.');
            }
            $this->show($this->empresa_default);
            $this->dispatch('cerrarModal');
        }
    }

    public function dias($dia, $id = false)
    {
        $parametro = Parametro::where('nombre', "horario_$dia")->where('tabla_id', $this->empresas_id)->first();
        if ($parametro){
            if ($id){
                return $parametro->id;
            }else{
                return $parametro->valor;
            }
        }else{
            return 0;
        }
    }

    public function verHorario()
    {

        $horario = Parametro::where('nombre', 'horario')->where('tabla_id', $this->empresas_id)->first();
        if ($horario){
            $this->horario_id = $horario->id;
            $this->horario = $horario->valor;
        }else{
            $this->horario_id = 0;
            $this->horario = 0;
        }

        $this->lunes = $this->dias('Mon');
        $this->martes = $this->dias('Tue');
        $this->miercoles = $this->dias('Wed');
        $this->jueves = $this->dias('Thu');
        $this->viernes = $this->dias('Fri');
        $this->sabado = $this->dias('Sat');
        $this->domingo = $this->dias('Sun');

        $apertura = Parametro::where('nombre', 'horario_apertura')->where('tabla_id', $this->empresas_id)->first();
        if ($apertura){
            $this->apertura = $apertura->valor;
        }else{
            $this->apertura = null;
        }

        $cierre = Parametro::where('nombre', 'horario_cierre')->where('tabla_id', $this->empresas_id)->first();
        if ($cierre){
            $this->cierre = $cierre->valor;
        }else{
            $this->cierre = null;
        }

        $this->title = "Datos de la Empresa";
        $this->btn_cancelar = true;
        $this->view = "horario";
    }

    public function botonHorario($id)
    {
        if ($id){
            $parametro = Parametro::find($id);
            if ($parametro){
                if ($parametro->valor == 1){
                    $parametro->valor = 0;
                }else{
                    $parametro->valor = 1;
                }
                $parametro->update();
            }else{
                $parametro = new Parametro();
                $parametro->nombre = "horario";
                $parametro->tabla_id = $this->empresas_id;
                $parametro->valor = 1;
                $parametro->save();
                $this->horario_id = $parametro->id;
            }
        }else{
            $parametro = new Parametro();
            $parametro->nombre = "horario";
            $parametro->tabla_id = $this->empresas_id;
            $parametro->valor = 1;
            $parametro->save();
            $this->horario_id = $parametro->id;
        }

        $this->horario = $parametro->valor;
    }

    public function diasActivos($dia, $valor)
    {
        $id = $this->dias($dia, true);
        if ($id){

            $parametro = Parametro::find($id);
            if ($valor == 0){
                $parametro->valor = 1;
            }else{
                $parametro->valor = 0;
            }
            $parametro->update();

        }else{

            $parametro = new Parametro();
            $parametro->nombre = "horario_$dia";
            $parametro->tabla_id = $this->empresas_id;
            $parametro->valor = 1;
            $parametro->save();
        }

        $this->lunes = $this->dias('Mon');
        $this->martes = $this->dias('Tue');
        $this->miercoles = $this->dias('Wed');
        $this->jueves = $this->dias('Thu');
        $this->viernes = $this->dias('Fri');
        $this->sabado = $this->dias('Sat');
        $this->domingo = $this->dias('Sun');
    }

    public function storeHoras()
    {
        $rules = [
            'apertura'  =>  'required_with:cierre',
            'cierre'    => 'required_with:apertura|after:apertura'
        ];
        $message = [
            'cierre.after'  =>  'cierre debe ser posterior a apertura. '
        ];

        $this->validate($rules, $message);

        $apertura = Parametro::where('nombre', 'horario_apertura')->where('tabla_id', $this->empresas_id)->first();
        if ($apertura){
            $apertura->valor = $this->apertura;
            $apertura->update();
        }else{
            $parametro = new Parametro();
            $parametro->nombre = "horario_apertura";
            $parametro->tabla_id = $this->empresas_id;
            $parametro->valor = $this->apertura;
            $parametro->save();
        }

        $cierre = Parametro::where('nombre', 'horario_cierre')->where('tabla_id', $this->empresas_id)->first();
        if ($cierre){
            $cierre->valor = $this->cierre;
            $cierre->update();
        }else{
            $parametro = new Parametro();
            $parametro->nombre = "horario_cierre";
            $parametro->tabla_id = $this->empresas_id;
            $parametro->valor = $this->cierre;
            $parametro->save();
        }

        $this->alert('success', 'Horas Guardadas.');
    }

    public function estatusTienda($id)
    {
        $estatus_tienda = Parametro::where('nombre', 'estatus_tienda')->where('tabla_id', $id)->first();
        if ($estatus_tienda){
            $parametro = Parametro::find($estatus_tienda->id);
            if ($parametro->valor == 1){
                $parametro->valor = 0;
            }else{
                $parametro->valor = 1;
            }
            $parametro->update();
        }else{
            $parametro = new Parametro();
            $parametro->nombre = "estatus_tienda";
            $parametro->tabla_id = $id;
            $parametro->valor = 1;
            $parametro->save();
        }
    }

    #[On('buscar')]
    public function buscar($keyword)
    {
        $this->keyword = $keyword;
    }

    public function btnBorrarImagen()
    {
        $this->verImagen = null;
        $this->reset('photo');
        $this->img_borrar_principal = $this->img_principal;
    }

    public function actualizar()
    {
        //
    }

    #[On('cerrarModal')]
    public function cerrarModal()
    {
        //JS
    }

}
