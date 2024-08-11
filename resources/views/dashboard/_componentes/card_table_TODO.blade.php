<div class="card card-navy" xmlns:wire="http://www.w3.org/1999/xhtml">

    <div class="card-header">
        <h3 class="card-title">
            @if(/*$keyword*/false)
                Busqueda { <b class="text-warning">{{ $keyword }}</b> }
                <button class="btn btn-tool text-warning" {{--wire:click="limpiarBuscar" onclick="verSpinnerOculto()"--}}>
                    <i class="fas fa-times-circle"></i>
                </button>
            @else
                Registrados [ <b class="text-warning">{{--{{ $keyword }}--}}0</b> ]
            @endif
        </h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" wire:click="setLimit" {{--@if($rows > $listarBienes->count()) disabled @endif--}} >
                <i class="fas fa-sort-amount-down-alt"></i> Ver más
            </button>
        </div>
    </div>

    <div class="card-body table-responsive p-0" @if(/*$tableStyle*/true) style="height: 72vh;" @endif>

        <table class="table table-head-fixed table-hover text-nowrap sticky-top">
            <thead>
            <tr class="text-navy">
                <th style="width: 10%">Código</th>
                <th>
                    Descripción
                    <small class="float-right">Mostrando {{--{{ $listarBienes->count() }}--}}0</small>
                </th>
            </tr>
            </thead>
        </table>

        <!-- TO DO List -->
        <ul class="todo-list" data-widget="todo-list">
            @if(/*$listarBienes->isNotEmpty()*/false)
                @foreach($listarBienes as $bien)
                    <li class=" @if($bien->id == $bienes_id) text-warning @endif " >
                        <!-- todo text -->
                        {{--<span class="text">
                                {{ $bien->codigo }}
                        </span>--}}
                        <!-- Emphasis label -->
                        <small class="text text-uppercase {{--badge-danger--}}">
                            {{ $bien->tipo->nombre }}
                            {{ $bien->marca->nombre }}
                            {{ $bien->modelo->nombre }}
                            @if(!is_null($bien->serial))
                                , Serial: {{ $bien->serial }}
                            @endif
                            @if(!is_null($bien->identificador))
                                , Identificador: {{ $bien->identificador }}
                            @endif

                        </small>
                        <!-- General tools such as edit or delete-->
                        <div class="tools text-primary" wire:click="show({{ $bien->id }})">
                            <i class="fas fa-eye"></i>
                        </div>
                    </li>
                @endforeach
            @else
                <li class="text-center">
                    <!-- todo text -->
                    @if(/*$keyword*/false)
                        <span class="text">Sin resultados</span>
                    @else
                        <span class="text">Sin registros guardados</span>
                    @endif
                </li>
            @endif

        </ul>
        <!-- /.TO DO List -->

    </div>

    <div class="overlay-wrapper" wire:loading wire:target="setLimit, save, destroy, confirmed">
        <div class="overlay">
            <div class="spinner-border text-navy" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    <div class="overlay-wrapper d-none cargar_">
        <div class="overlay">
            <div class="spinner-border text-navy" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>

</div>
