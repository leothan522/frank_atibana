<div wire:ignore.self class="modal fade" id="modal-roles-usuarios" xmlns:wire="http://www.w3.org/1999/xhtml">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content fondo">
            <div class="modal-header">

                <div class="row col-md-10">

                    <div class="col-md-6">
                        <h4 class="modal-title">
                            Rol de Usuario
                        </h4>
                    </div>

                    <div class="col-md-6 justify-content-end">
                        <form wire:submit="save">
                            <div class="input-group close">
                                <input type="text" class="form-control" placeholder="nombre" wire:model="nombre" equired>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active">Rol</li>
                            <li class="breadcrumb-item active">{{ ucfirst($nombre) }}</li>
                        </ol>
                    </div>
                </div>

                @if($roles_id)
                    @include('dashboard.usuarios.show_permisos')
                @endif

            </div>
            <div class="modal-footer">
                <div class="row col-12 justify-content-between">
                    <button type="button" class="btn btn-danger btn-sm" wire:click="destroy('{{ $rowquid }}')">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    <button type="button" class="btn btn-default btn-sm" wire:click="deletePermisos">
                        <i class="fas fa-trash-alt"></i> Quitar <span class="d-none d-md-inline">Todos</span>
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" wire:click="savePermisos" @if(!$cambios) disabled @endif>
                        <i class="fa fa-save"></i> Actualizar <span class="d-none d-md-inline">Permisos</span>
                    </button>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" wire:click="limpiarRoles" id="button_rol_modal_cerrar">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>

            <div class="overlay-wrapper" wire:loading wire:target="save, destroy, savePermisos, deletePermisos">
                <div class="overlay">
                    <div class="spinner-border text-navy" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>

            <div class="overlay-wrapper d-none" id="div_ver_spinner_roles">
                <div class="overlay">
                    <div class="spinner-border text-navy" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
