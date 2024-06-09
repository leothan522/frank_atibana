<?php

namespace App\Livewire\Dashboard;

use App\Models\Articulo;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class RecetasComponent extends Component
{
    use LivewireAlert;

    public $rows = 0, $numero = 14, $empresas_id, $tableStyle = false;

    public function mount()
    {
        $this->setLimit();
    }

    public function render()
    {
        return view('livewire.dashboard.recetas-component');
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

    #[On('getEmpresaRecetas')]
    public function getEmpresaRecetas($empresaID)
    {
        $this->empresas_id = $empresaID;
        $this->limpiar();
        /*$ultimo = Articulo::orderBy('codigo', 'ASC')->where('empresas_id', $this->empresas_id)->first();
        if ($ultimo) {
            $this->view = "show";
            $this->showArticulos($ultimo->id);
        }*/
    }

    public function limpiar()
    {
        $this->resetErrorBag();
        //$this->reset(['']);
    }

}
