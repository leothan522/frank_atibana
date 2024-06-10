<div class="card card-navy" style="height: inherit; width: inherit; transition: all 0.15s ease 0s;"
     xmlns:wire="http://www.w3.org/1999/xhtml">

    <div class="card-header">
        <h3 class="card-title">
            @if($new_proveedor)
                Nuevo Proveedor
            @endif
            @if(!$new_proveedor && $view == 'form')
                Editar Proveedor
            @endif
            @if($view != "form")
                Ver Proveedor
            @endif
        </h3>
        <div class="card-tools">
            {{--<span class="btn btn-tool"><i class="fas fa-list"></i></span>--}}
            @if($nuevo)
                <button class="btn btn-tool" wire:click="create"
                        @if(!comprobarPermisos('proveedores.create')) disabled @endif ><i class="fas fa-file"></i> Nuevo
                </button>
            @endif
            @if($edit)
                <button class="btn btn-tool" wire:click="btnEditar"
                        @if(!comprobarPermisos('proveedores.edit')) disabled @endif ><i class="fas fa-edit"></i> Editar
                </button>
            @endif
            @if($cancelar)
                <button class="btn btn-tool" wire:click="btnCancelar"><i class="fas fa-ban"></i> Cancelar</button>
            @endif
        </div>
    </div>

    <div class="card-body">

        @if($view == 'form')
            @include('dashboard.proveedores.form')
        @endif

        @if($view == 'show')
            @include('dashboard.proveedores.show')
        @endif

        @if($view != 'form' && $view != 'show')
            <div class="row m-5">
                Debes seleccionar un Proveedor ó Precionar el boton Nuevo para empezar...
            </div>
        @endif

    </div>

    <div class="card-footer text-center @if(!$footer) d-none @endif">

        {{--<a href="--}}{{--{{ route('proveedores.print', $proveedores_id) }}--}}{{--#" target="_blank"
           class="btn btn-default btn-sm @if(!comprobarPermisos('proveedores.print')) disabled @endif ">
            <i class="fas fa-print"></i> Imprimir
        </a>--}}

        <button type="button" class="btn btn-default btn-sm" wire:click="btnArticulos"
        data-toggle="modal" data-target="#modal-sm-articulos-proveedores"
                @if(!$estatus || !$btnVinculados) disabled @endif>
            <i class="fas fa-boxes"></i> Artículos Vinculados
        </button>

        <button type="button" class="btn btn-default btn-sm" wire:click="btnActivoInactivo"
                @if(!comprobarPermisos('proveedores.estatus')) disabled @endif >
            @if($estatus)
                <i class="fas fa-check"></i> Activo
            @else
                <i class="fas fa-ban"></i> Inactivo
            @endif
        </button>

        <button type="button" class="btn btn-default btn-sm" wire:click="destroy"
                @if(!comprobarPermisos('proveedores.destroy')) disabled @endif>
            <i class="fas fa-trash-alt"></i> Borrar
        </button>

    </div>

    <div class="overlay-wrapper" wire:loading
         wire:target="limpiar, create, save, show, photo, destroy, btnCancelar, btnEditar, btnActivoInactivo">
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
