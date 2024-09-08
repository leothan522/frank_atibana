<?php

namespace App\Livewire\Dashboard;

use App\Models\Almacen;
use App\Models\Empresa;
use App\Models\Parametro;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Sleep;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmpresasComponent extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $rows = 0, $numero = 14, $tableStyle = false;
    public $view = "show", $keyword, $title = "Datos de la Empresa", $btn_cancelar = false, $footer = true, $nuevo = true;
    public $empresa_default, $verDefault, $verImagen, $img_borrar_principal, $img_principal, $verMini;
    public $rif, $nombre, $jefe, $moneda, $telefonos, $email, $direccion, $photo, $default = 0, $permisos;
    public $horario, $horario_id, $lunes, $martes, $miercoles, $jueves, $viernes, $sabado, $domingo, $apertura, $cierre;
    public $srcImagen, $saveImagen = false;

    #[Locked]
    public $empresas_id, $rowquid;


    public function mount()
    {
        $this->setLimit();
        $empresas = Empresa::where('default', 1)->first();
        if ($empresas){
            $this->empresa_default = $empresas->rowquid;
            $this->show($this->empresa_default);
        }else{
            $this->create();
            $this->default = 1;
            $this->btn_cancelar = false;
        }
    }

    public function render()
    {
        $empresas = Empresa::buscar($this->keyword)
            ->orderBy('created_at', 'DESC')
            ->limit($this->rows)
            ->get();

        $total = Empresa::buscar($this->keyword)->count();

        $rows = Empresa::count();

        $rowsempresas = Empresa::count();
        if ($rowsempresas > $this->numero) {
            $this->tableStyle = true;
        }
        return view('livewire.dashboard.empresas-component')
            ->with('empresas', $empresas)
            ->with('rowsEmpresas', $rows)
            ->with('total', $total);
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
            'view', 'title', 'btn_cancelar', 'footer', 'empresas_id', 'verDefault', 'verImagen', 'nuevo',
            'rif', 'nombre', 'jefe', 'moneda', 'telefonos', 'email', 'direccion', 'photo', 'permisos', 'img_borrar_principal',
            'verMini', 'rowquid', 'srcImagen', 'saveImagen'
        ]);
        $this->resetErrorBag();
    }

    public function create()
    {
        $this->limpiar();
        $this->title = "Nueva Empresa";
        $this->btn_cancelar = true;
        $this->view = "form";
        $this->footer = false;
        $this->nuevo = false;
        $this->reset('srcImagen');

    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:1024', // 1MB Max
        ]);
        if ($this->img_principal){
            $this->img_borrar_principal = $this->img_principal;
        }

        $this->srcImagen = crearImagenTemporal($this->photo, 'empresas');
        $this->saveImagen = true;
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
        ];
    }

    public function save()
    {
        $this->validate();

        $imagen = null;

        if ($this->empresas_id){
            //editar
            $empresa = Empresa::find($this->empresas_id);
            if ($empresa){
                $imagen = $empresa->imagen;
            }
            $message = "Datos Guardados.";
        }else{
            //nuevo
            $empresa = new Empresa();
            $message = "Empresa Creada.";
            $empresa->default = $this->default;
            if ($this->default){
                $this->default = 0;
            }
            $permisos[Auth::id()] = true;
            $permisos = json_encode($permisos);
            $empresa->permisos = $permisos;
            do{
                $rowquid = generarStringAleatorio(16);
                $existe = Empresa::where('rowquid', $rowquid)->first();
            }while($existe);
            $empresa->rowquid = $rowquid;
        }

        if ($empresa){

            $empresa->rif = $this->rif;
            $empresa->nombre = $this->nombre;
            $empresa->supervisor = $this->jefe;
            $empresa->moneda = $this->moneda;
            $empresa->telefono = $this->telefonos;
            $empresa->email = $this->email;
            $empresa->direccion = $this->direccion;

            if ($this->photo && $this->saveImagen){
                $ruta = $this->photo->store('public/empresas');
                $empresa->imagen = str_replace('public/', 'storage/', $ruta);
                //miniaturas
                $nombre = explode('empresas/', $empresa->imagen);
                $path_data = "storage/empresas/size_".$nombre[1];
                $miniatura = crearMiniaturas($empresa->imagen, $path_data);
                $empresa->mini = $miniatura['mini'];
                //borramos la imagen temporal
                borrarImagenes($this->srcImagen, 'empresas');
                //borramos imagenes anteriones si existen
                if ($this->img_borrar_principal){
                    borrarImagenes($imagen, 'empresas');
                }
            }else{
                if ($this->img_borrar_principal){
                    $empresa->imagen = null;
                    $empresa->mini = null;
                    borrarImagenes($this->img_borrar_principal, 'empresas');
                }
            }

            $empresa->save();

            $this->show($empresa->rowquid);

            $this->alert('success', $message);

        }else{
            $this->show($this->empresa_default);
            $this->dispatch('cerrarModal');
        }
    }

    public function show($rowquid)
    {
        $this->limpiar();
        $empresa = $this->getEmpresa($rowquid);
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
            $this->rowquid = $empresa->rowquid;
            $this->srcImagen = $this->verMini;
            $this->view = "show";
        }else{
            Sleep::for(500)->millisecond();
            $this->dispatch('cerrarModal');
        }
    }

    public function edit()
    {
        $this->title = "Editar Empresa";
        $this->btn_cancelar = true;
        $this->view = "form";
    }

    public function convertirDefault()
    {
        $empresa = $this->getEmpresa($this->rowquid);
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

    public function destroy()
    {
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
        $empresa = $this->getEmpresa($this->rowquid);

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
                $imagen = $empresa->imagen;
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

    public function btnHorario()
    {

        $horario = Parametro::where('nombre', 'horario')->where('tabla_id', $this->empresas_id)->first();
        if ($horario) {
            $this->horario_id = $horario->id;
            $this->horario = $horario->valor;
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

    public function setHorario()
    {
        if ($this->horario_id){
            $parametro = Parametro::find($this->horario_id);
            $edit = true;
        }else{
            $parametro = new Parametro();
            $edit = false;
        }

        if ($parametro){
            if ($edit){
                if ($parametro->valor == 1){
                    $parametro->valor = 0;
                }else{
                    $parametro->valor = 1;
                }
            }else{
                $parametro->nombre = "horario";
                $parametro->tabla_id = $this->empresas_id;
                $parametro->valor = 1;
            }
        }
        $parametro->save();
        $this->horario_id = $parametro->id;
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

    public function setEstatusEmpresa($rowquid)
    {
        $empresa = $this->getEmpresa($rowquid);
        if ($empresa){
            $estatus_tienda = Parametro::where('nombre', 'estatus_tienda')->where('tabla_id', $empresa->id)->first();
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
                $parametro->tabla_id = $empresa->id;
                $parametro->valor = 1;
                $parametro->save();
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
        $this->reset(['keyword']);
        $this->btnCancelar();
    }

    public function btnBorrarImagen()
    {
        if ($this->saveImagen){
            $this->reset(['saveImagen', 'img_borrar_principal']);
            borrarImagenes($this->srcImagen, 'empresas');
            $this->srcImagen = $this->verMini;
        }else{
            $this->reset(['verImagen', 'srcImagen']);
            $this->img_borrar_principal = $this->img_principal;
        }
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

    protected function getEmpresa($rowquid): ?Empresa
    {
        return Empresa::where('rowquid', $rowquid)->first();
    }

    public function getEstatusTienda($rowquid): int
    {
        $estatus = 1;
        $empresa = $this->getEmpresa($rowquid);
        if ($empresa){
            $parametro = Parametro::where('nombre', 'estatus_tienda')->where('tabla_id', $empresa->id)->first();
            if ($parametro){
                $estatus = intval($parametro->valor);
            }else{
                $estatus = 0;
            }
        }
        return $estatus;
    }

    public function btnCancelar()
    {
        if ($this->empresas_id){
            $this->show($this->rowquid);
        }else{
            $this->show($this->empresa_default);
        }
    }



}
