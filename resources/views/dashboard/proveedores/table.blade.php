<div class="card card-navy" xmlns:wire="http://www.w3.org/1999/xhtml">

    <div class="card-header">
        <h3 class="card-title">
            @if($keyword)
                Búsqueda { <b class="text-warning">{{ $keyword }}</b> }
                <button class="btn btn-tool text-warning" wire:click="cerrarBusqueda"><i class="fas fa-times-circle"></i>
                </button>
            @else
                Proveedores [ <b class="text-warning">{{ $rowsProveedores }}</b> ]
            @endif
        </h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" wire:click="actualizar">
                <i class="fas fa-sync-alt"></i>
            </button>
            <button type="button" class="btn btn-tool" wire:click="setLimit" @if($rows > $listarProveedores->count()) disabled @endif>
                <i class="fas fa-sort-amount-down-alt"></i> Ver más
            </button>
        </div>
    </div>

    <div class="card-body table-responsive p-0" @if($tableStyle) style="height: 76vh;" @endif >

        <table class="table table-head-fixed table-hover text-nowrap sticky-top">
            <thead>
            <tr class="text-navy">
                <th style="width: 10%">RIF</th>
                <th>
                    Nombre
                    <small class="float-right">Mostrando {{ $listarProveedores->count() }}</small>
                </th>
            </tr>
            </thead>
        </table>

        <!-- TO DO List -->
        <ul class="todo-list" data-widget="todo-list">
            @if($listarProveedores->isNotEmpty())
                @foreach($listarProveedores as $proveedor)
                    <li class=" @if(!$proveedor->estatus) done @endif @if($proveedor->id == $proveedores_id) text-warning @endif "" >
                    <!-- todo text -->
                    <span class="text text-uppercase">
                            {{ $proveedor->rif }}
                        </span>
                    <!-- Emphasis label -->
                    <small class="badge {{--badge-danger--}} text-uppercase">
                        {{ $proveedor->nombre }}
                    </small>
                    <!-- General tools such as edit or delete-->
                    <div class="tools text-primary" wire:click="show({{ $proveedor->id }})">
                        <i class="fas fa-eye"></i>
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
         wire:target="save, destroy, setLimit, btnActivoInactivo, cerrarBusqueda">
        <div class="overlay">
            <div class="spinner-border text-navy" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>

    <div class="overlay-wrapper d-none cargar_proveedores">
        <div class="overlay">
            <div class="spinner-border text-navy" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>

</div>

