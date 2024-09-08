<div class="card card-navy">

    <div class="card-header">
        <h3 class="card-title">
            @if($keyword)
                Búsqueda { <b class="text-warning">{{ $keyword }}</b> } [ <b class="text-warning">{{ $total }}</b> ]
                <button class="btn btn-tool text-warning" wire:click="cerrarBusqueda">
                    <i class="fas fa-times-circle"></i>
                </button>
            @else
                Empresas [ <b class="text-warning">{{ $rowsEmpresas }}</b> ]
            @endif
        </h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool d-sm-none" wire:click="actualizar">
                <i class="fas fa-sync-alt"></i>
            </button>
            <button type="button" class="btn btn-tool d-sm-none" data-toggle="modal" data-target="#modal-default"
                    wire:click="create" @if(!comprobarPermisos('empresas.create')) disabled @endif>
                <i class="fas fa-file"></i> Nuevo
            </button>
            <button type="button" class="btn btn-tool" wire:click="setLimit" @if($rows >= $rowsEmpresas) disabled @endif>
                <i class="fas fa-sort-amount-down-alt"></i> Ver más
            </button>
        </div>
    </div>

    <div class="card-body table-responsive p-0" @if($tableStyle) style="height: 68vh;" @endif >

        <table class="table table-head-fixed table-hover text-nowrap sticky-top">
            <thead>
            <tr class="text-navy">
                {{--<th style="width: 10%">Código</th>--}}
                <th>
                    Nombre
                    <small class="float-right">Mostrando {{ $total }}</small>
                </th>
            </tr>
            </thead>
        </table>

        <!-- TO DO List -->
        <ul class="todo-list" data-widget="todo-list">
            @if($empresas->isNotEmpty())
                @foreach($empresas as $empresa)
                    <li class=" @if($empresa->id == $empresas_id) text-warning @endif ">

                        <!-- todo text -->
                        <span class="text"
                              @if(comprobarPermisos('empresas.estatus') || comprobarAccesoEmpresa($empresa->permisos, auth()->id())) style="cursor: pointer"
                              wire:click="setEstatusEmpresa('{{ $empresa->rowquid }}')" @endif >
                                <i class="fas fa-power-off @if($this->getEstatusTienda($empresa->rowquid)) text-success @else text-danger @endif"></i>
                        </span>

                        <!-- Emphasis label -->
                        <small class="badge" wire:click="show('{{ $empresa->rowquid }}')" style="cursor: pointer;">
                            <span class="text-uppercase d-none d-md-inline-block text-truncate" style="max-width: 250px;">
                                @if($empresa->default)
                                    <i class="fas fa-certificate text-muted text-xs"></i>
                                @endif
                                {{ $empresa->nombre }}
                            </span>
                            <span class="text-uppercase d-inline-block d-md-none text-truncate" style="max-width: 230px;"
                                  data-toggle="modal" data-target="#modal-default">
                                @if($empresa->default)
                                    <i class="fas fa-certificate text-muted text-xs"></i>
                                @endif
                                {{ $empresa->nombre }}
                            </span>
                        </small>

                        <!-- General tools such as edit or delete-->
                        <div class="tools text-primary" wire:click="show('{{ $empresa->rowquid }}')">
                            <i class="fas fa-eye d-none d-md-inline-block"></i>
                            <i class="fas fa-eye d-md-none" data-toggle="modal" data-target="#modal-default"></i>
                        </div>

                    </li>
                @endforeach
            @else
                <li class="text-center">
                    <!-- todo text -->
                    @if($keyword)
                        <span class="text">Sin resultados</span>
                    @else
                        <span class="text">Sin registros guardados</span>
                    @endif
                </li>
            @endif

        </ul>
        <!-- /.TO DO List -->

    </div>

    <div class="overlay-wrapper" wire:loading
         wire:target="setLimit, save, convertirDefault, destroy, confirmed, actualizar">
        <div class="overlay">
            <div class="spinner-border text-navy" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    <div class="overlay-wrapper d-none cargar_empresas">
        <div class="overlay">
            <div class="spinner-border text-navy" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>

</div>

