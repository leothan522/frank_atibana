<?php

namespace App\Livewire\Dashboard;

use App\Models\Parametro;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class UsuariosComponent extends Component
{
    use LivewireAlert;

    public $rows = 0, $numero = 14, $tableStyle = false;
    public $view = "create", $keyword;
    public $name, $email, $password, $role, $usuarios_id;
    public $edit_name, $edit_email, $edit_password, $edit_role = 0, $edit_roles_id = 0, $created_at, $estatus = 1, $photo, $empresas_id;
    public $rol_nombre, $tabla = 'usuarios', $getPermisos, $cambios = false;

    public function mount()
    {
        $this->setLimit();
    }

    public function render()
    {
        $roles = Parametro::where('tabla_id', '-1')->get();

        $users = User::buscar($this->keyword)
            ->orderBy('role', 'DESC')
            ->orderBy('roles_id', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->limit($this->rows)
            ->get();

        $rows = User::count();

        if ($rows > $this->numero) {
            $this->tableStyle = true;
        }

        return view('livewire.dashboard.usuarios-component')
            ->with('listarRoles', $roles)
            ->with('listarUsers', $users)
            ->with('rowsUsuarios', $rows);
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

    #[On('limpiar')]
    public function limpiar()
    {
        $this->reset([
            'view', 'keyword', 'name', 'email', 'password', 'role', 'usuarios_id',
            'edit_name', 'edit_email', 'edit_password', 'edit_role', 'edit_roles_id', 'created_at', 'estatus',
            'photo', 'empresas_id', 'rol_nombre', 'getPermisos', 'cambios'
        ]);
        $this->resetErrorBag();
    }

    public function generarClave()
    {
        $this->password = Str::password(8);
    }

    protected function rules($id = null)
    {
        if ($id) {
            $rules = [
                'edit_name' => 'required|min:4',
                'edit_email' => ['required', 'email', Rule::unique('users', 'email')->ignore($id)],
            ];
        } else {
            $rules = [
                'name' => 'required|min:4',
                'email' => ['required', 'email', Rule::unique('users')],
                'password' => 'required|min:8',
                'role' => 'required'
            ];
        }
        return $rules;
    }

    protected $messages = [

        'edit_name.required' => 'El campo nombre es obligatorio.',
        'edit_name.min' => 'El campo nombre debe contener al menos 4 caracteres.',
        'edit_email.required' => 'El campo correo electrónico es obligatorio.',
        'edit_email.email' => 'El campo correo electrónico no es un correo válido.',

    ];

    public function save()
    {
        $this->validate($this->rules($this->usuarios_id));

        if (is_null($this->usuarios_id)) {
            //nuevo
            $usuarios = new User();
            $usuarios->name = ucwords($this->name);
            $usuarios->email = strtolower($this->email);
            $usuarios->password = Hash::make($this->password);
            if ($this->role > 1) {
                $usuarios->role = 2;
                $usuarios->roles_id = $this->role;
                $role = Parametro::where('tabla_id', '-1')->where('id', $this->role)->first();
                if ($role) {
                    $usuarios->permisos = $role->valor;
                }
            } else {
                $usuarios->role = $this->role;
                $usuarios->roles_id = null;
            }
            $usuarios->save();
            $this->limpiar();
            $this->alert('success', 'Usuario Creado.');
        } else {
            //editar
            $usuarios = User::find($this->usuarios_id);
            if ($usuarios){
                $usuarios->name = ucwords($this->edit_name);
                $usuarios->email = strtolower($this->edit_email);
                if ($this->edit_role > 1) {
                    $usuarios->role = 2;
                    $usuarios->roles_id = $this->edit_role;
                    $role = Parametro::where('tabla_id', '-1')->where('id', $this->edit_role)->first();
                    if ($role) {
                        $usuarios->permisos = $role->valor;
                    }
                } else {
                    $usuarios->role = $this->edit_role;
                    $usuarios->roles_id = null;
                }
                $usuarios->save();
                $this->edit($this->usuarios_id);
                $this->alert('success', 'Usuario Actualizado.');
            }else{
                $this->dispatch('cerrarModal');
            }
        }
    }

    public function edit($id)
    {
        $this->limpiar();
        $usuario = User::find($id);
        if ($usuario){
            $this->usuarios_id = $usuario->id;
            $this->edit_name = $usuario->name;
            $this->edit_email = $usuario->email;
            if ($usuario->roles_id) {
                $this->edit_role = $usuario->roles_id;
            }else{
                $this->edit_role = $usuario->role;
            }
            $this->edit_roles_id = $usuario->roles_id;
            $this->estatus = $usuario->estatus;
            $this->created_at = $usuario->created_at;
            $this->photo = $usuario->profile_photo_path;
            /*$this->empresas_id = $usuario->empresas_id;*/
            $this->rol_nombre = verRole($usuario->role, $usuario->roles_id);
            $this->getPermisos = $usuario->permisos;
        }else{
            $this->dispatch('cerrarModal');
        }
    }

    public function cambiarEstatus($id)
    {
        $usuario = User::find($id);
        if ($usuario){
            if ($usuario->estatus) {
                $usuario->estatus = 0;
                $texto = "Usuario Suspendido";
            } else {
                $usuario->estatus = 1;
                $texto = "Usuario Activado";
            }
            $usuario->update();
            $this->estatus = $usuario->estatus;
            $this->alert('success', $texto);
        }else{
            $this->dispatch('cerrarModal');
        }
    }

    public function restablecerClave($id)
    {
        $usuario = User::find($id);
        if ($usuario){
            if (!$this->edit_password) {
                $clave = Str::password(8);
            } else {
                $clave = $this->edit_password;
            }
            $usuario->password = Hash::make($clave);
            $usuario->update();
            $this->edit_password = $clave;
            $this->alert('success', 'Contraseña Restablecida');
        }else{
            $this->dispatch('cerrarModal');
        }
    }

    public function destroy($id)
    {
        $this->usuario_id = $id;
        $this->confirm('¿Estas seguro?', [
            'toast' => false,
            'position' => 'center',
            'showConfirmButton' => true,
            'confirmButtonText' => '¡Sí, bórralo!',
            'text' => '¡No podrás revertir esto!',
            'cancelButtonText' => 'No',
            'onConfirmed' => 'confirmedUser',
        ]);
    }

    #[On('confirmedUser')]
    public function confirmedUser()
    {
        $usuario = User::find($this->usuarios_id);
        if ($usuario){
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
                $usuario->delete();
                $this->limpiar();
                $this->dispatch('cerrarModal');
                $this->alert(
                    'success',
                    'Usuario Eliminado.'
                );
            }
        }else{
            $this->dispatch('cerrarModal');
        }
    }

    #[On('buscar')]
    public function buscar($keyword)
    {
        $this->keyword = $keyword;
    }

    #[On('cerrarModal')]
    public function cerrarModal()
    {
        //JS
    }

    public function setPermisos($permiso)
    {
        $permisos = [];
        if (!leerJson($this->getPermisos, $permiso)){
            $permisos = json_decode($this->getPermisos, true);
            $permisos[$permiso] = true;
            $permisos = json_encode($permisos);
            $message = "Permiso Agregado.";
        }else{
            $permisos = json_decode($this->getPermisos, true);
            unset($permisos[$permiso]);
            $permisos = json_encode($permisos);
            $message = "Permiso Eliminado.";
        }
        $this->getPermisos = $permisos;
        $this->cambios = true;
    }

    public function savePermisos(){
        $usuario = User::find($this->usuarios_id);
        if ($usuario){
            $usuario->permisos = $this->getPermisos;
            $usuario->save();
            $this->reset('cambios');
            $this->alert('success', 'Permisos Guardados.');
        }
    }

    public function deletePermisos()
    {
        $this->reset('getPermisos');
        $this->cambios = true;
    }

    public function getEstatusUsuario($i, $icon = null)
    {
        if (is_null($icon)){
            $suspendido = "Suspendido";
            $activado = "Activo";
        }else{
            $suspendido = '<i class="fa fa-user-slash"></i>';
            $activado = '<i class="fa fa-user-check"></i>';
        }
        $status = [
            '0' => '<span class="text-danger">'.$suspendido.'</span>',
            '1' => '<span class="text-success">'.$activado.'</span>',
            /*'2' => '<span class="text-success">Confirmado</span>'*/
        ];
        return $status[$i];
    }

}
