<form @if($new_planificacion) wire:submit="save" @else wire:submit="update" @endif xmlns:wire="http://www.w3.org/1999/xhtml">

    <div class="row col-12 mb-2">
        <div class="col-md-2">
            <label>Código:</label>
        </div>
        <div class="col-md-2 mb-2">
            <input type="text" class="form-control form-control-sm @error('codigo') is-invalid @enderror" placeholder="Código"
                   wire:model="codigo" readonly>
        </div>
        <div class="col-md-3">
            &nbsp; {{--@error('codigo') {{ $message }} @endif--}}
        </div>
        <div class="col-md-2 text-md-right">
            <label>Semana:</label>
        </div>
        <div class="col-md-3">
            <input type="week" class="form-control form-control-sm @error('fecha') is-invalid @enderror"
                   wire:model="fecha" >
        </div>
    </div>

    {{--<div class="row col-12 mb-2">
        <div class="col-md-2">
            <label>Descripción:</label>
        </div>
        <div class="col-md-6">
            <input type="text" class="form-control form-control-sm @error('descripcion') is-invalid @enderror" placeholder="Descripción"
                   wire:model="descripcion">
        </div>
        <div class="col-md-4">
            <input type="number" class="form-control form-control-sm @error('cantidad') is-invalid @enderror" placeholder="Cantidad (KG)"
                   wire:model="cantidad" min="0.001" step=".001" >
        </div>
    </div>--}}

    <div class="col-12">
        <div class="card card-navy card-outline card-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link @if($dia == 'lunes') active @endif " id="custom-tabs-three-lunes-tab" data-toggle="pill" href="#custom-tabs-three-lunes" role="tab" aria-controls="custom-tabs-three-lunes" aria-selected="@if($dia == 'lunes') true @else false @endif " wire:click="setDia('lunes')"> @if($contador_lunes) <i class="fas fa-check"></i> @endif Lunes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if($dia == 'martes') active @endif " id="custom-tabs-three-martes-tab" data-toggle="pill" href="#custom-tabs-three-martes" role="tab" aria-controls="custom-tabs-three-martes" aria-selected="@if($dia == 'martes') true @else false @endif" wire:click="setDia('martes')"> @if($contador_martes) <i class="fas fa-check"></i> @endif Martes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if($dia == 'miercoles') active @endif " id="custom-tabs-three-miercoles-tab" data-toggle="pill" href="#custom-tabs-three-miercoles" role="tab" aria-controls="custom-tabs-three-miercoles" aria-selected="@if($dia == 'miercoles') true @else false @endif" wire:click="setDia('miercoles')"> @if($contador_miercoles) <i class="fas fa-check"></i> @endif Miercoles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if($dia == 'jueves') active @endif " id="custom-tabs-three-jueves-tab" data-toggle="pill" href="#custom-tabs-three-jueves" role="tab" aria-controls="custom-tabs-three-jueves" aria-selected="@if($dia == 'jueves') true @else false @endif" wire:click="setDia('jueves')"> @if($contador_jueves) <i class="fas fa-check"></i> @endif Jueves</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if($dia == 'viernes') active @endif " id="custom-tabs-three-viernes-tab" data-toggle="pill" href="#custom-tabs-three-viernes" role="tab" aria-controls="custom-tabs-three-viernes" aria-selected="@if($dia == 'viernes') true @else false @endif" wire:click="setDia('viernes')"> @if($contador_viernes) <i class="fas fa-check"></i> @endif Viernes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if($dia == 'sabado') active @endif " id="custom-tabs-three-sabado-tab" data-toggle="pill" href="#custom-tabs-three-sabado" role="tab" aria-controls="custom-tabs-three-sabado" aria-selected="@if($dia == 'sabado') true @else false @endif" wire:click="setDia('sabado')"> @if($contador_sabado) <i class="fas fa-check"></i> @endif Sabado</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if($dia == 'domingo') active @endif " id="custom-tabs-three-domingo-tab" data-toggle="pill" href="#custom-tabs-three-domingo" role="tab" aria-controls="custom-tabs-three-domingo" aria-selected="@if($dia == 'domingo') true @else false @endif" wire:click="setDia('domingo')"> @if($contador_domingo) <i class="fas fa-check"></i> @endif Domingo</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-three-tabContent">

                    @include('dashboard.planificacion._layout.form_lunes')

                    @include('dashboard.planificacion._layout.form_martes')

                    @include('dashboard.planificacion._layout.form_miercoles')

                    @include('dashboard.planificacion._layout.form_jueves')

                    @include('dashboard.planificacion._layout.form_viernes')

                    @include('dashboard.planificacion._layout.form_sabado')

                    @include('dashboard.planificacion._layout.form_domingo')

                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @if($errors->has('codigoRecetaLunes.*') || $errors->has('cantidadLunes.*') || $errors->has('codigoRecetaMartes.*') || $errors->has('cantidadMartes.*') || $errors->has('codigoRecetaMiercoles.*') || $errors->has('cantidadMiercoles.*') || $errors->has('codigoRecetaJueves.*') || $errors->has('cantidadJueves.*') || $errors->has('codigoRecetaViernes.*') || $errors->has('cantidadViernes.*') || $errors->has('codigoRecetaSabado.*') || $errors->has('cantidadSabado.*') || $errors->has('codigoRecetaDomingo.*') || $errors->has('cantidadDomingo.*'))
                <span class="col-sm-12 text-sm text-bold text-danger">
                    <i class="icon fas fa-exclamation-triangle"></i>
                    Todos los campos son obigatorios y deben ser validados.
                </span>
            @endif
        </div>
    </div>

    <div class="row col-12 justify-content-end">
        <div class="col-md-4 float-right mt-3">
            <button type="submit" class="btn btn-block btn-success">
                <i class="fas fa-save"></i> Guardar
            </button>
        </div>
    </div>

</form>
@include('dashboard.planificacion.modal_buscar')
