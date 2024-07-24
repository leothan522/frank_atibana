<?php

namespace App\Livewire\Dashboard;

use App\Models\Despacho;
use App\Models\DespSegmento;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class SegmentosDespachosComponent extends Component
{
    use LivewireAlert;

    public $rows = 0;
    public $segmentos_id, $nombre, $keyword;

    public function mount()
    {
        $this->setLimit();
    }

    public function render()
    {
        $segmentos = DespSegmento::buscar($this->keyword)
            ->orderBy('id', 'ASC')
            ->limit($this->rows)
            ->get()
        ;
        $rowsSegmento = DespSegmento::count();

        return view('livewire.dashboard.segmentos-despachos-component')
            ->with('listarSegmentos', $segmentos)
            ->with('rowsSegmento', $rowsSegmento);
    }

    public function setLimit()
    {
        if (numRowsPaginate() < 10) { $rows = 10; } else { $rows = numRowsPaginate(); }
        $this->rows = $this->rows + $rows;
    }

    #[On('limpiarSegmentos')]
    public function limpiarSegmentos()
    {
        $this->reset([
            'segmentos_id', 'nombre', 'keyword'
        ]);
    }

    public function save()
    {
        $rules = [
            'nombre' => ['required', 'min:4', 'max:15'/*, 'alpha_dash:ascii'*/, Rule::unique('despachos_segmentos', 'descripcion')->ignore($this->segmentos_id)],
        ];
        $messages = [
            'nombre.required' => 'El campo descripción es obligatorio.',
            'nombre.min' => 'El campo descripción debe contener al menos 4 caracteres.',
            'nombre.max' => 'El campo descripción no debe ser mayor que 15 caracteres.',
            'nombre.alpha_num' => ' El campo descripción sólo debe contener letras y números.',
            'nombre.unique' => ' El campo descripción ya ha sido registrado.',
        ];

        $this->validate($rules, $messages);
        $message = null;
        if (is_null($this->segmentos_id)) {
            //nuevo
            $tipo = new DespSegmento();
            $message = "Segmento Creado.";
        } else {
            //editar
            $tipo = DespSegmento::find($this->segmentos_id);
            $message = "Segmento Actualizado.";
        }

        if ($tipo){
            $tipo->descripcion = ucfirst($this->nombre);
            $tipo->save();
            $this->alert('success', $message);
        }

        $this->limpiarSegmentos();

    }

    public function edit($id)
    {
        $segmentos = DespSegmento::find($id);
        if ($segmentos){
            $this->segmentos_id = $segmentos->id;
            $this->nombre = $segmentos->descripcion;
        }
    }

    public function destroy($id)
    {
        $this->segmentos_id = $id;
        $this->confirm('¿Estas seguro?', [
            'toast' => false,
            'position' => 'center',
            'showConfirmButton' => true,
            'confirmButtonText' => '¡Sí, bórralo!',
            'text' => '¡No podrás revertir esto!',
            'cancelButtonText' => 'No',
            'onConfirmed' => 'confirmedSegmento',
        ]);

    }

    #[On('confirmedSegmento')]
    public function confirmedSegmento()
    {

        $segmentos = DespSegmento::find($this->segmentos_id);

        //codigo para verificar si realmente se puede borrar, dejar false si no se requiere validacion
        $vinculado = false;
        $detalles = Despacho::where('segmentos_id', $this->segmentos_id)->first();

        if ($detalles){
            $vinculado = true;
        }

        if ($vinculado) {
            $this->reset('segmentos_id');
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
            if ($segmentos){
                $segmentos->delete();
                $this->alert('success', 'Segmento Eliminado.');
            }
            $this->limpiarSegmentos();
        }
    }

    public function buscar()
    {
        //
    }
}
