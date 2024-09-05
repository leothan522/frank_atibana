<div wire:ignore.self class="modal fade" id="modal-user-permisos" xmlns:wire="http://www.w3.org/1999/xhtml">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content fondo">
            <div class="modal-header bg-navy">
                <h4 class="modal-title">
                    Permisos de Usuario
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="text-white" aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active d-inline-block text-truncate" style="max-width: 200px;">{{ $edit_name }}</li>
                            <li class="breadcrumb-item active d-inline-block text-truncate" style="max-width: 200px;" >{{ $edit_email }}</li>
                            <li class="breadcrumb-item active" >{{ $rol_nombre }}</li>
                            <li class="breadcrumb-item active" >{!! $this->getEstatusUsuario($estatus, true) !!}</li>
                        </ol>
                    </div>
                </div>

                @if($users_id)
                    @include('dashboard.usuarios.show_permisos')
                @endif

            </div>
            <div class="modal-footer">
                <div class="row col-12 justify-content-between">
                    <button type="button" class="btn btn-default btn-sm" wire:click="deletePermisos">
                        <i class="fas fa-trash-alt"></i> Quitar <span class="d-none d-md-inline">Todos</span>
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" wire:click="savePermisos" @if(!$cambios) disabled @endif>
                        <i class="fa fa-save"></i> Actualizar <span class="d-none d-md-inline">Permisos</span>
                    </button>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" wire:click="limpiar" id="button_permisos_modal_cerrar">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>

            <div class="overlay-wrapper" wire:loading wire:target="edit, savePermisos, deletePermisos">
                <div class="overlay">
                    <div class="spinner-border text-navy" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>

            <div class="overlay-wrapper d-none" id="div_ver_spinner_usuarios">
                <div class="overlay">
                    <div class="spinner-border text-navy" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
