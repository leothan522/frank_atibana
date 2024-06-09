<div class="card card-navy" style="height: inherit; width: inherit; transition: all 0.15s ease 0s;"
     xmlns:wire="http://www.w3.org/1999/xhtml">

    <div class="card-header">
        <h3 class="card-title">
            @if($new_receta)
                Nuevo Receta
            @endif
            @if(!$new_receta && $view == 'form')
                Editar Receta
            @endif
            @if($view != "form")
                Ver Receta
            @endif
        </h3>
        <div class="card-tools">
            {{--<span class="btn btn-tool"><i class="fas fa-list"></i></span>--}}
            @if($nuevo)
                <button class="btn btn-tool" wire:click="create"
                        @if(!comprobarPermisos('recetas.create')) disabled @endif ><i class="fas fa-file"></i> Nuevo
                </button>
            @endif
            @if($edit)
                <button class="btn btn-tool" wire:click="btnEditar"
                        @if(!comprobarPermisos('recetas.edit')) disabled @endif ><i class="fas fa-edit"></i> Editar
                </button>
            @endif
            @if($cancelar)
                <button class="btn btn-tool" wire:click="btnCancelar"><i class="fas fa-ban"></i> Cancelar</button>
            @endif
        </div>
    </div>

    <div class="card-body">

        @if($view == 'form')
            @include('dashboard.recetas.form')
        @endif

        @if($view == 'show')
            @include('dashboard.recetas.show')
        @endif

        @if($view != 'form' && $view != 'show')
            <div class="row m-5">
                Debes seleccionar una Receta ó Precionar el boton Nuevo para empezar...
            </div>
        @endif

    </div>

    <div class="card-footer text-center @if(!$footer) d-none @endif">

        <a href="{{ route('recetas.print', $recetas_id) }}" target="_blank"
           class="btn btn-default btn-sm @if(!comprobarPermisos('recetas.print')) disabled @endif ">
            <i class="fas fa-print"></i> Imprimir
        </a>

        <button type="button" class="btn btn-default btn-sm" wire:click="btnActivoInactivo"
                @if(!comprobarPermisos('recetas.estatus')) disabled @endif >
            @if($estatus)
                <i class="fas fa-check"></i> Activo
            @else
                <i class="fas fa-ban"></i> Inactivo
            @endif
        </button>

        <button type="button" class="btn btn-default btn-sm" wire:click="destroy"
                @if(!comprobarPermisos()) disabled @endif>
            <i class="fas fa-trash-alt"></i> Borrar
        </button>

    </div>

    <div class="overlay-wrapper" wire:loading
         wire:target="limpiar, create, save, show, update, destroy, btnCancelar, btnEditar, btnActivoInactivo">
        <div class="overlay">
            <div class="spinner-border text-navy" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>

    <div class="overlay-wrapper d-none cargar_recetas">
        <div class="overlay">
            <div class="spinner-border text-navy" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>

</div>
