<?php

namespace App\Livewire\Dashboard;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RecetasComponent extends Component
{
    use LivewireAlert;

    public function render()
    {
        return view('livewire.dashboard.recetas-component');
    }

}
