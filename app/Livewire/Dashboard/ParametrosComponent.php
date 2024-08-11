<?php

namespace App\Livewire\Dashboard;

use App\Models\Parametro;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class ParametrosComponent extends Component
{
    use LivewireAlert;

    public $rows = 0, $numero = 14, $tableStyle = false;
    public $view = "create", $keyword;
    public $parametro_id, $nombre, $tabla_id, $valor;

    public function mount()
    {
        $this->setLimit();
    }

    public function render()
    {
        $parametros = Parametro::buscar($this->keyword)
            ->orderBy('created_at', 'DESC')
            ->limit($this->rows)
            ->get();

        $rows = Parametro::count();

        if ($rows > $this->numero) {
            $this->tableStyle = true;
        }

        return view('livewire.dashboard.parametros-component')
            ->with('parametros', $parametros)
            ->with('rowsParametros', $rows);
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
            'parametro_id', 'nombre', 'tabla_id', 'valor', 'view', 'keyword'
        ]);
        $this->resetErrorBag();
    }

    protected function rules($id = null)
    {
        $rules = [
            'nombre' => ['required', 'min:3', 'alpha_dash', Rule::unique('parametros', 'nombre')->ignore($id)],
            'tabla_id' => 'nullable|integer'
        ];
        return $rules;
    }

    public function save()
    {

        $this->validate($this->rules($this->parametro_id));

        if (is_null($this->parametro_id)){
            //nuevo
            $parametro = new Parametro();
            $message = "Parametro Creado";
        }else{
            //editar
            $parametro = Parametro::find($this->parametro_id);
            $message = "Parametro Actualizado";
        }

        if ($parametro){
            $parametro->nombre = $this->nombre;
            if (!empty($this->tabla_id)){
                $parametro->tabla_id = $this->tabla_id;
            }
            if (!empty($this->valor)){
                $parametro->valor = $this->valor;
            }
            $parametro->save();

            $this->alert('success', $message);
        }
        $this->limpiar();
        $this->dispatch('cerrarModal');
    }

    public function edit($id)
    {
        $parametro = Parametro::find($id);
        if ($parametro){
            $this->parametro_id = $parametro->id;
            $this->nombre = $parametro->nombre;
            $this->tabla_id = $parametro->tabla_id;
            $this->valor = $parametro->valor;
            $this->view = "edit";
        }
    }

    #[On('buscar')]
    public function buscar($keyword)
    {
        $this->keyword = $keyword;
    }

    public function destroy($id)
    {
        $this->parametro_id = $id;
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
        $parametro = Parametro::find($this->parametro_id);
        if ($parametro){
            $parametro->delete();
            $this->limpiar();
            $this->alert('success', 'Parametro Eliminado.');
        }
    }

    #[On('cerrarModal')]
    public function cerrarModal()
    {
        //JV
    }

}
