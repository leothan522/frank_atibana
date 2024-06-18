<?php

namespace App\Livewire\Dashboard;

use App\Models\Receta;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class PlanificacionComponent extends Component
{
    use LivewireAlert;

    public $rows = 0, $numero = 14, $empresas_id, $tableStyle = false;
    public $view, $nuevo = true, $cancelar = false, $footer = false, $edit = false, $new_planificacion = false, $keyword;

    public $contador_lunes = 0, $idRecetaLunes = [], $codigoRecetaLunes = [], $classRecetaLunes = [],
            $descripcionRecetaLunes = [], $cantidadLunes = [], $detalles_id_lunes = [];

    public $contador_martes = 0, $idRecetaMartes = [], $codigoRecetaMartes = [], $classRecetaMartes = [],
        $descripcionRecetaMartes = [], $cantidadMartes = [], $detalles_id_martes = [];

    public $contador_miercoles = 0, $idRecetaMiercoles = [], $codigoRecetaMiercoles = [], $classRecetaMiercoles = [],
        $descripcionRecetaMiercoles = [], $cantidadMiercoles = [], $detalles_id_miercoles = [];

    public $contador_jueves = 0, $idRecetaJueves = [], $codigoRecetaJueves = [], $classRecetaJueves = [],
        $descripcionRecetaJueves = [], $cantidadJueves = [], $detalles_id_jueves = [];

    public $contador_viernes = 0, $idRecetaViernes = [], $codigoRecetaViernes = [], $classRecetaViernes = [],
        $descripcionRecetaViernes = [], $cantidadViernes = [], $detalles_id_viernes = [];

    public $contador_sabado = 0, $idRecetaSabado = [], $codigoRecetaSabado = [], $classRecetaSabado = [],
        $descripcionRecetaSabado = [], $cantidadSabado = [], $detalles_id_sabado = [];

    public $contador_domingo = 0, $idRecetaDomingo = [], $codigoRecetaDomingo = [], $classRecetaDomingo = [],
        $descripcionRecetaDomingo = [], $cantidadDomingo = [], $detalles_id_domingo = [];

    public $borraritems = [], $item, $dia = 'lunes', $listarRecetas, $keywordRecetas;
    public $planificaciones_id, $codigo, $fecha, $descripcion, $estatus;

    public function mount()
    {
        $this->setLimit();
    }

    public function render()
    {
        return view('livewire.dashboard.planificacion-component');
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

    #[On('getEmpresaPlanificacion')]
    public function getEmpresaPlanificacion($empresaID)
    {
        $this->empresas_id = $empresaID;
        //$this->limpiar();
        /*$ultimo = Articulo::orderBy('codigo', 'ASC')->where('empresas_id', $this->empresas_id)->first();
        if ($ultimo) {
            $this->view = "show";
            $this->showArticulos($ultimo->id);
        }*/
    }

    public function limpiar()
    {
        $this->resetErrorBag();
        $this->reset([
            'view', 'nuevo', 'cancelar', 'footer', 'new_planificacion',

            'contador_lunes', 'idRecetaLunes', 'codigoRecetaLunes', 'classRecetaLunes', 'descripcionRecetaLunes',
            'cantidadLunes', 'detalles_id_lunes',

            'contador_martes', 'idRecetaMartes', 'codigoRecetaMartes', 'classRecetaMartes', 'descripcionRecetaMartes',
            'cantidadMartes', 'detalles_id_martes',

            'contador_miercoles', 'idRecetaMiercoles', 'codigoRecetaMiercoles', 'classRecetaMiercoles', 'descripcionRecetaMiercoles',
            'cantidadMiercoles', 'detalles_id_miercoles',

            'contador_jueves', 'idRecetaJueves', 'codigoRecetaJueves', 'classRecetaJueves', 'descripcionRecetaJueves',
            'cantidadJueves', 'detalles_id_jueves',

            'contador_viernes', 'idRecetaViernes', 'codigoRecetaViernes', 'classRecetaViernes', 'descripcionRecetaViernes',
            'cantidadViernes', 'detalles_id_viernes',

            'contador_sabado', 'idRecetaSabado', 'codigoRecetaSabado', 'classRecetaSabado', 'descripcionRecetaSabado',
            'cantidadSabado', 'detalles_id_sabado',

            'contador_domingo', 'idRecetaDomingo', 'codigoRecetaDomingo', 'classRecetaDomingo', 'descripcionRecetaDomingo',
            'cantidadDomingo', 'detalles_id_domingo',

            'borraritems', 'item', 'dia', 'listarRecetas', 'keywordRecetas'
        ]);
    }

    public function create()
    {
        $this->limpiar();
        $this->new_planificacion = true;
        $this->view = "form";
        $this->nuevo = false;
        $this->cancelar = true;
        $this->edit = false;
        $this->footer = false;
    }

    protected function rules()
    {
        return [
            'fecha' => 'required',
            'codigoRecetaLunes.*' => [Rule::requiredIf($this->contador_lunes > 0), Rule::exists('recetas', 'codigo')],
            'cantidadLunes.*' => [Rule::requiredIf($this->contador_lunes > 0)],
            'codigoRecetaMartes.*' => [Rule::requiredIf($this->contador_martes > 0), Rule::exists('recetas', 'codigo')],
            'cantidadMartes.*' => [Rule::requiredIf($this->contador_martes > 0)],
            'codigoRecetaMiercoles.*' => [Rule::requiredIf($this->contador_miercoles > 0), Rule::exists('recetas', 'codigo')],
            'cantidadMiercoles.*' => [Rule::requiredIf($this->contador_miercoles > 0)],
            'codigoRecetaJueves.*' => [Rule::requiredIf($this->contador_jueves > 0), Rule::exists('recetas', 'codigo')],
            'cantidadJueves.*' => [Rule::requiredIf($this->contador_jueves > 0)],
            'codigoRecetaViernes.*' => [Rule::requiredIf($this->contador_viernes > 0), Rule::exists('recetas', 'codigo')],
            'cantidadViernes.*' => [Rule::requiredIf($this->contador_viernes > 0)],
            'codigoRecetaSabado.*' => [Rule::requiredIf($this->contador_sabado > 0), Rule::exists('recetas', 'codigo')],
            'cantidadSabado.*' => [Rule::requiredIf($this->contador_sabado > 0)],
            'codigoRecetaDomingo.*' => [Rule::requiredIf($this->contador_domingo > 0), Rule::exists('recetas', 'codigo')],
            'cantidadDomingo.*' => [Rule::requiredIf($this->contador_domingo > 0)],
        ];
    }

    public function save()
    {
        $this->validate();

        $key = '-W';
        $valido = strpos($this->fecha, $key);

        if ($valido !== false){

            //obteniendo fechas para la semana
            $carbon = CarbonImmutable::now();
            $explode = explode($key, $this->fecha);
            $semana = intval($explode[1]);
            $date = Carbon::parse($carbon->week($semana)->format('d-m-Y'));
            $lunes = $date->startOfWeek()->format('Y-m-d');
            $martes = Carbon::parse($lunes)->addDay()->format('Y-m-d');
            $miercoles = Carbon::parse($lunes)->addDay(2)->format('Y-m-d');
            $jueves = Carbon::parse($lunes)->addDay(3)->format('Y-m-d');
            $viernes = Carbon::parse($lunes)->addDay(4)->format('Y-m-d');
            $sabado = Carbon::parse($lunes)->addDay(5)->format('Y-m-d');
            $domingo = $date->endOfWeek()->format('Y-m-d');




        }else{
            dd('error');
        }

    }

    public function btnContador($opcion, $dia)
    {
        switch ($dia){

            case 'lunes':

                if ($opcion == "add") {
                    $this->idRecetaLunes[$this->contador_lunes] = null;
                    $this->codigoRecetaLunes[$this->contador_lunes] = null;
                    $this->classRecetaLunes[$this->contador_lunes] = null;
                    $this->descripcionRecetaLunes[$this->contador_lunes] = null;
                    $this->cantidadLunes[$this->contador_lunes] = null;
                    $this->detalles_id_lunes[$this->contador_lunes] = null;
                    $this->contador_lunes++;
                } else {

                    if ($this->detalles_id_lunes[$opcion]) {
                        $this->borraritems[] = [
                            'id' => $this->detalles_id_lunes[$opcion]
                        ];
                    }

                    for ($i = $opcion; $i < $this->contador_lunes - 1; $i++) {
                        $this->idRecetaLunes[$i] = $this->idRecetaLunes[$i + 1];
                        $this->codigoRecetaLunes[$i] = $this->codigoRecetaLunes[$i + 1];
                        $this->classRecetaLunes[$i] = $this->classRecetaLunes[$i + 1];
                        $this->descripcionRecetaLunes[$i] = $this->descripcionRecetaLunes[$i + 1];
                        $this->cantidadLunes[$i] = $this->cantidadLunes[$i + 1];
                        $this->detalles_id_lunes[$i] = $this->detalles_id_lunes[$i + 1];
                    }
                    $this->contador_lunes--;
                    unset($this->idRecetaLunes[$this->contador_lunes]);
                    unset($this->codigoRecetaLunes[$this->contador_lunes]);
                    unset($this->classRecetaLunes[$this->contador_lunes]);
                    unset($this->descripcionRecetaLunes[$this->contador_lunes]);
                    unset($this->cantidadLunes[$this->contador_lunes]);
                    unset($this->detalles_id_lunes[$this->contador_lunes]);
                }

                break;

            case 'martes':

                if ($opcion == "add") {
                    $this->idRecetaMartes[$this->contador_martes] = null;
                    $this->codigoRecetaMartes[$this->contador_martes] = null;
                    $this->classRecetaMartes[$this->contador_martes] = null;
                    $this->descripcionRecetaMartes[$this->contador_martes] = null;
                    $this->cantidadMartes[$this->contador_martes] = null;
                    $this->detalles_id_martes[$this->contador_martes] = null;
                    $this->contador_martes++;
                } else {

                    if ($this->detalles_id_martes[$opcion]) {
                        $this->borraritems[] = [
                            'id' => $this->detalles_id_martes[$opcion]
                        ];
                    }

                    for ($i = $opcion; $i < $this->contador_martes - 1; $i++) {
                        $this->idRecetaMartes[$i] = $this->idRecetaMartes[$i + 1];
                        $this->codigoRecetaMartes[$i] = $this->codigoRecetaMartes[$i + 1];
                        $this->classRecetaMartes[$i] = $this->classRecetaMartes[$i + 1];
                        $this->descripcionRecetaMartes[$i] = $this->descripcionRecetaMartes[$i + 1];
                        $this->cantidadMartes[$i] = $this->cantidadMartes[$i + 1];
                        $this->detalles_id_martes[$i] = $this->detalles_id_martes[$i + 1];
                    }
                    $this->contador_martes--;
                    unset($this->idRecetaMartes[$this->contador_martes]);
                    unset($this->codigoRecetaMartes[$this->contador_martes]);
                    unset($this->classRecetaMartes[$this->contador_martes]);
                    unset($this->descripcionRecetaMartes[$this->contador_martes]);
                    unset($this->cantidadMartes[$this->contador_martes]);
                    unset($this->detalles_id_martes[$this->contador_martes]);
                }

                break;

            case 'miercoles':

                if ($opcion == "add") {
                    $this->idRecetaMiercoles[$this->contador_miercoles] = null;
                    $this->codigoRecetaMiercoles[$this->contador_miercoles] = null;
                    $this->classRecetaMiercoles[$this->contador_miercoles] = null;
                    $this->descripcionRecetaMiercoles[$this->contador_miercoles] = null;
                    $this->cantidadMiercoles[$this->contador_miercoles] = null;
                    $this->detalles_id_miercoles[$this->contador_miercoles] = null;
                    $this->contador_miercoles++;
                } else {

                    if ($this->detalles_id_miercoles[$opcion]) {
                        $this->borraritems[] = [
                            'id' => $this->detalles_id_miercoles[$opcion]
                        ];
                    }

                    for ($i = $opcion; $i < $this->contador_miercoles - 1; $i++) {
                        $this->idRecetaMiercoles[$i] = $this->idRecetaMiercoles[$i + 1];
                        $this->codigoRecetaMiercoles[$i] = $this->codigoRecetaMiercoles[$i + 1];
                        $this->classRecetaMiercoles[$i] = $this->classRecetaMiercoles[$i + 1];
                        $this->descripcionRecetaMiercoles[$i] = $this->descripcionRecetaMiercoles[$i + 1];
                        $this->cantidadMiercoles[$i] = $this->cantidadMiercoles[$i + 1];
                        $this->detalles_id_miercoles[$i] = $this->detalles_id_miercoles[$i + 1];
                    }
                    $this->contador_miercoles--;
                    unset($this->idRecetaMiercoles[$this->contador_miercoles]);
                    unset($this->codigoRecetaMiercoles[$this->contador_miercoles]);
                    unset($this->classRecetaMiercoles[$this->contador_miercoles]);
                    unset($this->descripcionRecetaMiercoles[$this->contador_miercoles]);
                    unset($this->cantidadMiercoles[$this->contador_miercoles]);
                    unset($this->detalles_id_miercoles[$this->contador_miercoles]);
                }

                break;

            case 'jueves':

                if ($opcion == "add") {
                    $this->idRecetaJueves[$this->contador_jueves] = null;
                    $this->codigoRecetaJueves[$this->contador_jueves] = null;
                    $this->classRecetaJueves[$this->contador_jueves] = null;
                    $this->descripcionRecetaJueves[$this->contador_jueves] = null;
                    $this->cantidadJueves[$this->contador_jueves] = null;
                    $this->detalles_id_jueves[$this->contador_jueves] = null;
                    $this->contador_jueves++;
                } else {

                    if ($this->detalles_id_jueves[$opcion]) {
                        $this->borraritems[] = [
                            'id' => $this->detalles_id_jueves[$opcion]
                        ];
                    }

                    for ($i = $opcion; $i < $this->contador_jueves - 1; $i++) {
                        $this->idRecetaJueves[$i] = $this->idRecetaJueves[$i + 1];
                        $this->codigoRecetaJueves[$i] = $this->codigoRecetaJueves[$i + 1];
                        $this->classRecetaJueves[$i] = $this->classRecetaJueves[$i + 1];
                        $this->descripcionRecetaJueves[$i] = $this->descripcionRecetaJueves[$i + 1];
                        $this->cantidadJueves[$i] = $this->cantidadJueves[$i + 1];
                        $this->detalles_id_jueves[$i] = $this->detalles_id_jueves[$i + 1];
                    }
                    $this->contador_jueves--;
                    unset($this->idRecetaJueves[$this->contador_jueves]);
                    unset($this->codigoRecetaJueves[$this->contador_jueves]);
                    unset($this->classRecetaJueves[$this->contador_jueves]);
                    unset($this->descripcionRecetaJueves[$this->contador_jueves]);
                    unset($this->cantidadJueves[$this->contador_jueves]);
                    unset($this->detalles_id_Jueves[$this->contador_jueves]);
                }

                break;

            case 'viernes':

                if ($opcion == "add") {
                    $this->idRecetaViernes[$this->contador_viernes] = null;
                    $this->codigoRecetaViernes[$this->contador_viernes] = null;
                    $this->classRecetaViernes[$this->contador_viernes] = null;
                    $this->descripcionRecetaViernes[$this->contador_viernes] = null;
                    $this->cantidadViernes[$this->contador_viernes] = null;
                    $this->detalles_id_viernes[$this->contador_viernes] = null;
                    $this->contador_viernes++;
                } else {

                    if ($this->detalles_id_viernes[$opcion]) {
                        $this->borraritems[] = [
                            'id' => $this->detalles_id_viernes[$opcion]
                        ];
                    }

                    for ($i = $opcion; $i < $this->contador_viernes - 1; $i++) {
                        $this->idRecetaViernes[$i] = $this->idRecetaViernes[$i + 1];
                        $this->codigoRecetaViernes[$i] = $this->codigoRecetaViernes[$i + 1];
                        $this->classRecetaViernes[$i] = $this->classRecetaViernes[$i + 1];
                        $this->descripcionRecetaViernes[$i] = $this->descripcionRecetaViernes[$i + 1];
                        $this->cantidadViernes[$i] = $this->cantidadViernes[$i + 1];
                        $this->detalles_id_viernes[$i] = $this->detalles_id_viernes[$i + 1];
                    }
                    $this->contador_viernes--;
                    unset($this->idRecetaViernes[$this->contador_viernes]);
                    unset($this->codigoRecetaViernes[$this->contador_viernes]);
                    unset($this->classRecetaViernes[$this->contador_viernes]);
                    unset($this->descripcionRecetaViernes[$this->contador_viernes]);
                    unset($this->cantidadViernes[$this->contador_viernes]);
                    unset($this->detalles_id_viernes[$this->contador_viernes]);
                }

                break;

            case 'sabado':

                if ($opcion == "add") {
                    $this->idRecetaSabado[$this->contador_sabado] = null;
                    $this->codigoRecetaSabado[$this->contador_sabado] = null;
                    $this->classRecetaSabado[$this->contador_sabado] = null;
                    $this->descripcionRecetaSabado[$this->contador_sabado] = null;
                    $this->cantidadSabado[$this->contador_sabado] = null;
                    $this->detalles_id_sabado[$this->contador_sabado] = null;
                    $this->contador_sabado++;
                } else {

                    if ($this->detalles_id_sabado[$opcion]) {
                        $this->borraritems[] = [
                            'id' => $this->detalles_id_sabado[$opcion]
                        ];
                    }

                    for ($i = $opcion; $i < $this->contador_sabado - 1; $i++) {
                        $this->idRecetaSabado[$i] = $this->idRecetaSabado[$i + 1];
                        $this->codigoRecetaSabado[$i] = $this->codigoRecetaSabado[$i + 1];
                        $this->classRecetaSabado[$i] = $this->classRecetaSabado[$i + 1];
                        $this->descripcionRecetaSabado[$i] = $this->descripcionRecetaSabado[$i + 1];
                        $this->cantidadSabado[$i] = $this->cantidadSabado[$i + 1];
                        $this->detalles_id_sabado[$i] = $this->detalles_id_sabado[$i + 1];
                    }
                    $this->contador_sabado--;
                    unset($this->idRecetaSabado[$this->contador_sabado]);
                    unset($this->codigoRecetaSabado[$this->contador_sabado]);
                    unset($this->classRecetaSabado[$this->contador_sabado]);
                    unset($this->descripcionRecetaSabado[$this->contador_sabado]);
                    unset($this->cantidadSabado[$this->contador_sabado]);
                    unset($this->detalles_id_sabado[$this->contador_sabado]);
                }

                break;

            case 'domingo':

                if ($opcion == "add") {
                    $this->idRecetaDomingo[$this->contador_domingo] = null;
                    $this->codigoRecetaDomingo[$this->contador_domingo] = null;
                    $this->classRecetaDomingo[$this->contador_domingo] = null;
                    $this->descripcionRecetaDomingo[$this->contador_domingo] = null;
                    $this->cantidadDomingo[$this->contador_domingo] = null;
                    $this->detalles_id_domingo[$this->contador_domingo] = null;
                    $this->contador_domingo++;
                } else {

                    if ($this->detalles_id_domingo[$opcion]) {
                        $this->borraritems[] = [
                            'id' => $this->detalles_id_domingo[$opcion]
                        ];
                    }

                    for ($i = $opcion; $i < $this->contador_domingo - 1; $i++) {
                        $this->idRecetaDomingo[$i] = $this->idRecetaDomingo[$i + 1];
                        $this->codigoRecetaDomingo[$i] = $this->codigoRecetaDomingo[$i + 1];
                        $this->classRecetaDomingo[$i] = $this->classRecetaDomingo[$i + 1];
                        $this->descripcionRecetaDomingo[$i] = $this->descripcionRecetaDomingo[$i + 1];
                        $this->cantidadDomingo[$i] = $this->cantidadDomingo[$i + 1];
                        $this->detalles_id_domingo[$i] = $this->detalles_id_domingo[$i + 1];
                    }
                    $this->contador_domingo--;
                    unset($this->idRecetaDomingo[$this->contador_domingo]);
                    unset($this->codigoRecetaDomingo[$this->contador_domingo]);
                    unset($this->classRecetaDomingo[$this->contador_domingo]);
                    unset($this->descripcionRecetaDomingo[$this->contador_domingo]);
                    unset($this->cantidadDomingo[$this->contador_domingo]);
                    unset($this->detalles_id_domingo[$this->contador_domingo]);
                }

                break;

        }
    }

    public function itemTemporal($int)
    {
        $this->item = $int;
    }

    public function buscarRecetas()
    {
        $this->listarRecetas = Receta::buscar($this->keywordRecetas)
            ->where('empresas_id', $this->empresas_id)
            ->where('estatus', 1)
            ->limit(100)
            ->get();
    }

    public function selectReceta($codigo)
    {
        switch ($this->dia){

            case 'lunes':
                $this->codigoRecetaLunes[$this->item] = $codigo;
                $this->updatedCodigoRecetaLunes();
                break;

            case 'martes':
                $this->codigoRecetaMartes[$this->item] = $codigo;
                $this->updatedCodigoRecetaMartes();
                break;

            case 'miercoles':
                $this->codigoRecetaMiercoles[$this->item] = $codigo;
                $this->updatedCodigoRecetaMiercoles();
                break;

            case 'jueves':
                $this->codigoRecetaJueves[$this->item] = $codigo;
                $this->updatedCodigoRecetaJueves();
                break;

            case 'viernes':
                $this->codigoRecetaViernes[$this->item] = $codigo;
                $this->updatedCodigoRecetaViernes();
                break;

            case 'sabado':
                $this->codigoRecetaSabado[$this->item] = $codigo;
                $this->updatedCodigoRecetaSabado();
                break;

            case 'domingo':
                $this->codigoRecetaDomingo[$this->item] = $codigo;
                $this->updatedCodigoRecetaDomingo();
                break;

        }
    }

    public function updatedCodigoRecetaLunes()
    {
        foreach ($this->codigoRecetaLunes as $key => $value) {
            $array = array();
            if ($value) {
                $receta = Receta::where('codigo', $value)
                    ->where('empresas_id', $this->empresas_id)
                    ->where('estatus', 1)
                    ->first();
                if ($receta) {
                    $this->classRecetaLunes[$key] = "is-valid";
                    $this->descripcionRecetaLunes[$key] = mb_strtoupper($receta->descripcion);
                    $this->idRecetaLunes[$key] = $receta->id;
                    $this->resetErrorBag('codigoRecetaLunes.' . $key);
                } else {
                    $this->classRecetaLunes[$key] = "is-invalid";
                    $this->descripcionRecetaLunes[$key] = null;
                    $this->idRecetaLunes[$key] = null;
                }
            }
        }
    }

    public function updatedCodigoRecetaMartes()
    {
        foreach ($this->codigoRecetaMartes as $key => $value) {
            $array = array();
            if ($value) {
                $receta = Receta::where('codigo', $value)
                    ->where('empresas_id', $this->empresas_id)
                    ->where('estatus', 1)
                    ->first();
                if ($receta) {
                    $this->classRecetaMartes[$key] = "is-valid";
                    $this->descripcionRecetaMartes[$key] = mb_strtoupper($receta->descripcion);
                    $this->idRecetaMartes[$key] = $receta->id;
                    $this->resetErrorBag('codigoRecetaMartes.' . $key);
                } else {
                    $this->classRecetaMartes[$key] = "is-invalid";
                    $this->descripcionRecetaMartes[$key] = null;
                    $this->idRecetaMartes[$key] = null;
                }
            }
        }
    }

    public function updatedCodigoRecetaMiercoles()
    {
        foreach ($this->codigoRecetaMiercoles as $key => $value) {
            $array = array();
            if ($value) {
                $receta = Receta::where('codigo', $value)
                    ->where('empresas_id', $this->empresas_id)
                    ->where('estatus', 1)
                    ->first();
                if ($receta) {
                    $this->classRecetaMiercoles[$key] = "is-valid";
                    $this->descripcionRecetaMiercoles[$key] = mb_strtoupper($receta->descripcion);
                    $this->idRecetaMiercoles[$key] = $receta->id;
                    $this->resetErrorBag('codigoRecetaMiercoles.' . $key);
                } else {
                    $this->classRecetaMiercoles[$key] = "is-invalid";
                    $this->descripcionRecetaMiercoles[$key] = null;
                    $this->idRecetaMiercoles[$key] = null;
                }
            }
        }
    }

    public function updatedCodigoRecetaJueves()
    {
        foreach ($this->codigoRecetaJueves as $key => $value) {
            $array = array();
            if ($value) {
                $receta = Receta::where('codigo', $value)
                    ->where('empresas_id', $this->empresas_id)
                    ->where('estatus', 1)
                    ->first();
                if ($receta) {
                    $this->classRecetaJueves[$key] = "is-valid";
                    $this->descripcionRecetaJueves[$key] = mb_strtoupper($receta->descripcion);
                    $this->idRecetaJueves[$key] = $receta->id;
                    $this->resetErrorBag('codigoRecetaJueves.' . $key);
                } else {
                    $this->classRecetaJueves[$key] = "is-invalid";
                    $this->descripcionRecetaJueves[$key] = null;
                    $this->idRecetaJueves[$key] = null;
                }
            }
        }
    }

    public function updatedCodigoRecetaViernes()
    {
        foreach ($this->codigoRecetaViernes as $key => $value) {
            $array = array();
            if ($value) {
                $receta = Receta::where('codigo', $value)
                    ->where('empresas_id', $this->empresas_id)
                    ->where('estatus', 1)
                    ->first();
                if ($receta) {
                    $this->classRecetaViernes[$key] = "is-valid";
                    $this->descripcionRecetaViernes[$key] = mb_strtoupper($receta->descripcion);
                    $this->idRecetaViernes[$key] = $receta->id;
                    $this->resetErrorBag('codigoRecetaViernes.' . $key);
                } else {
                    $this->classRecetaViernes[$key] = "is-invalid";
                    $this->descripcionRecetaViernes[$key] = null;
                    $this->idRecetaViernes[$key] = null;
                }
            }
        }
    }

    public function updatedCodigoRecetaSabado()
    {
        foreach ($this->codigoRecetaSabado as $key => $value) {
            $array = array();
            if ($value) {
                $receta = Receta::where('codigo', $value)
                    ->where('empresas_id', $this->empresas_id)
                    ->where('estatus', 1)
                    ->first();
                if ($receta) {
                    $this->classRecetaSabado[$key] = "is-valid";
                    $this->descripcionRecetaSabado[$key] = mb_strtoupper($receta->descripcion);
                    $this->idRecetaSabado[$key] = $receta->id;
                    $this->resetErrorBag('codigoRecetaSabado.' . $key);
                } else {
                    $this->classRecetaSabado[$key] = "is-invalid";
                    $this->descripcionRecetaSabado[$key] = null;
                    $this->idRecetaSabado[$key] = null;
                }
            }
        }
    }

    public function updatedCodigoRecetaDomingo()
    {
        foreach ($this->codigoRecetaDomingo as $key => $value) {
            $array = array();
            if ($value) {
                $receta = Receta::where('codigo', $value)
                    ->where('empresas_id', $this->empresas_id)
                    ->where('estatus', 1)
                    ->first();
                if ($receta) {
                    $this->classRecetaDomingo[$key] = "is-valid";
                    $this->descripcionRecetaDomingo[$key] = mb_strtoupper($receta->descripcion);
                    $this->idRecetaDomingo[$key] = $receta->id;
                    $this->resetErrorBag('codigoRecetaDomingo.' . $key);
                } else {
                    $this->classRecetaDomingo[$key] = "is-invalid";
                    $this->descripcionRecetaDomingo[$key] = null;
                    $this->idRecetaDomingo[$key] = null;
                }
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
        $this->reset('keyword');
        $this->limpiar();
    }

    public function btnCancelar()
    {
        if ($this->planificaciones_id) {
            //$this->show($this->recetas_id);
        } else {
            $this->limpiar();
        }
    }

    public function setDia($dia)
    {
        $this->dia = $dia;
    }

}
