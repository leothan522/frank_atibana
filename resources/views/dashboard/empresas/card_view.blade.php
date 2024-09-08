<div class="card card-navy d-none d-sm-block">
    <div class="card-header">
        <h3 class="card-title">
            {{ $title }}
        </h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" wire:click="actualizar">
                <i class="fas fa-sync-alt"></i>
            </button>
            @if($nuevo)
                <button type="button" class="btn btn-tool" wire:click="create" @if(!comprobarPermisos('empresas.create')) disabled @endif>
                    <i class="fas fa-file"></i> Nuevo
                </button>
            @endif
            @if($btn_cancelar)
                <button type="button" class="btn btn-tool" wire:click="btnCancelar">
                    <i class="fas fa-ban"></i> Cancelar
                </button>
            @endif
        </div>
    </div>
    <div class="card-body">

        @include('dashboard.empresas.'.$view)

    </div>

    @if($footer)
        <div class="card-footer text-center @if(!comprobarAccesoEmpresa($permisos, auth()->id())) d-none @endif">

            @if(!$verDefault)
                @if(auth()->user()->role == 100)
                    <button type="button" class="btn btn-default btn-sm mr-1" wire:click="destroy"
                            @if(!comprobarPermisos('empresas.destroy')) disabled @endif>
                        <i class="fas fa-trash-alt"></i> Borrar Empresa
                    </button>
                @endif
                <button type="button" class="btn btn-default btn-sm mr-1" wire:click="convertirDefault"
                        @if(!comprobarPermisos('empresas.edit')) disabled @endif>
                    <i class="fas fa-certificate"></i> Convertir en Default
                </button>
            @endif

            <button type="button" class="btn btn-default btn-sm" wire:click="btnHorario"
                    @if(!comprobarPermisos('empresas.horario')) disabled @endif>
                <i class="fas fa-clock"></i> Horario
            </button>

            <button type="button" class="btn btn-default btn-sm" wire:click="edit"
                    @if(!comprobarPermisos('empresas.edit')) disabled @endif>
                <i class="fas fa-edit"></i> Editar Información
            </button>

        </div>
    @endif

    <div class="overlay-wrapper" wire:loading wire:target="limpiar, create, show, save, edit, convertirDefault,
                destroy, btnHorario, setHorario, {{--diasActivos,--}} storeHoras, actualizar, btnCancelar, btnBorrarImagen">
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
