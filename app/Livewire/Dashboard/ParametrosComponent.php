<?php

namespace App\Livewire\Dashboard;

use App\Models\Parametro;
use Illuminate\Support\Sleep;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class ParametrosComponent extends Component
{
    use LivewireAlert;

    public $rows = 0, $numero = 14, $tableStyle = false;
    public $view = "create", $keyword;
    public $nombre, $tabla_id, $valor;

    #[Locked]
    public $parametros_id, $rowquid;

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

        $total = Parametro::buscar($this->keyword)->count();

        $rows = Parametro::count();

        if ($rows > $this->numero) {
            $this->tableStyle = true;
        }

        return view('livewire.dashboard.parametros-component')
            ->with('parametros', $parametros)
            ->with('rowsParametros', $rows)
            ->with('totalBusqueda', $total);
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
            'parametros_id', 'nombre', 'tabla_id', 'valor', 'view', 'rowquid'
        ]);
        $this->resetErrorBag();
    }

    protected function rules($id = null): array
    {
        $rules = [
            'nombre' => ['required', 'min:3', 'alpha_dash', Rule::unique('parametros', 'nombre')->ignore($id)],
            'tabla_id' => 'nullable|integer'
        ];
        return $rules;
    }

    public function save()
    {

        $this->validate($this->rules($this->parametros_id));

        if (is_null($this->parametros_id)){
            //nuevo
            $parametro = new Parametro();
            $message = "Parametro Creado.";
            do{
                $rowquid = generarStringAleatorio(16);
                $existe = Parametro::where('rowquid', '=', $rowquid)->first();
            }while($existe);
            $parametro->rowquid = $rowquid;
        }else{
            //editar
            $parametro = Parametro::find($this->parametros_id);
            $message = "Parametro Actualizado.";
        }

        if ($parametro){
            $parametro->nombre = $this->nombre;
            if (!empty($this->tabla_id)){
                $parametro->tabla_id = $this->tabla_id;
            }
            $parametro->valor = $this->valor;
            $parametro->save();

            if ($message == "Parametro Creado."){
                $this->reset('keyword');
            }
            $this->alert('success', $message);
        }
        $this->limpiar();
        $this->dispatch('cerrarModal');
    }

    public function edit($rowquid)
    {
        $this->limpiar();
        $parametro = $this->getParametro($rowquid);
        if ($parametro){
            $this->parametros_id = $parametro->id;
            $this->nombre = $parametro->nombre;
            $this->tabla_id = $parametro->tabla_id;
            $this->valor = $parametro->valor;
            $this->rowquid = $parametro->rowquid;
            $this->view = "edit";
        }else{
            Sleep::for(500)->millisecond();
            $this->dispatch('cerrarModal');
        }

    }

    #[On('buscar')]
    public function buscar($keyword)
    {
        $this->keyword = $keyword;
    }

    public function destroy($rowquid)
    {
        $this->rowquid = $rowquid;
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
        $parametro = $this->getParametro($this->rowquid);
        if ($parametro){
            $parametro->delete();
            $this->limpiar();
            $this->alert('success', 'Parametro Eliminado.');
        }
    }

    #[On('cerrarModal')]
    public function cerrarModal()
    {
        //JS
    }

    public function cerrarBusqueda()
    {
        $this->reset('keyword');
        $this->limpiar();
    }

    protected function getParametro($rowquid): ?Parametro
    {
        return Parametro::where('rowquid', $rowquid)->first();
    }

}
