@if($planificaciones_id)
    <div class="row col-12 mb-2">
        <div class="col-md-2">
            <label>Código:</label>
        </div>
        <div class="col-md-5 mb-2">
            <span class="border badge-pill text-uppercase">{{ $codigo }}</span>
        </div>
        <div class="col-md-2 text-md-right">
            <label>Semana:</label>
        </div>
        <div class="col-md-3">
            <span class="border badge-pill text-nowrap">{{ $verSemana }}</span>
        </div>
    </div>

    <div class="row col-12 mb-2">
        <div class="col-md-2">
            <label>Descripción:</label>
        </div>
        <div class="col-md-8">
            <span class="border badge-pill text-uppercase">{{ $descripcion }}</span>
        </div>
        {{--@if($cantidad)
            <div class="col-md-3 text-md-right">
                <label>Cantidad (KG):</label>
            </div>
            <div class="col-md-1">
                <span class="border badge-pill text-uppercase text-nowrap">{{ formatoMillares($cantidad, 3) }}</span>
            </div>
        @endif--}}
    </div>

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

                    @include('dashboard.planificacion._layout.show_lunes')

                    @include('dashboard.planificacion._layout.show_martes')

                    @include('dashboard.planificacion._layout.show_miercoles')

                    @include('dashboard.planificacion._layout.show_jueves')

                    @include('dashboard.planificacion._layout.show_viernes')

                    @include('dashboard.planificacion._layout.show_sabado')

                    @include('dashboard.planificacion._layout.show_domingo')

                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>

@endif
