<div wire:ignore.self class="modal fade" id="modal-sm-articulos-proveedores" xmlns:wire="http://www.w3.org/1999/xhtml">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-navy">
                <h4 class="modal-title">Proveedores</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="text-white" aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="card-body p-0">

                <div class="table-responsive p-0" style="height: 40vh;">

                    <table class="table table-sm table-head-fixed table-hover {{--text-nowrap--}}">
                        <thead>
                        <tr class="text-navy">
                            <th class="">Nombre</th>
                            <th style="width: 5%">&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($listarArticulosProveedores->isNotEmpty())
                            @php($favorito = true)
                            @foreach($listarArticulosProveedores as $proveedor)
                                <tr>
                                    <td class="text-uppercase">
                                        {{ $proveedor->proveedor->nombre }}
                                        <small class="badge float-right">
                                            @if($favorito)
                                                Favorito
                                                @php($favorito = false)
                                            @else
                                                <i class="fas fa-level-up-alt"></i>
                                            @endif
                                        </small>
                                    </td>
                                    <td class="text-right">
                                        @if(comprobarPermisos('articulos.edit'))
                                            <button class="btn btn-sm text-danger m-0 @if(!comprobarPermisos('articulos.proveedores')) d-none @endif " wire:click="destroy({{ $proveedor->id }})">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2" class="text-center text-danger">
                                    Sin Proveedor asignado.
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>


                </div>

                <form wire:submit="save" class="p-0 @if(!comprobarPermisos('articulos.proveedores')) d-none @endif ">
                    <table class="table table-sm">
                        <tbody>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <div class="input-group">
                                        <select class="custom-select custom-select-sm @error("proveedores_id") is-invalid @enderror" wire:model="proveedores_id" id="articulos_select_proveedores">
                                            <option value="">Seleccione</option>
                                            @foreach($listarProveedores as $proveedor)
                                                @if($proveedor->ver)
                                                    <option value="{{ $proveedor->id }}">{{ mb_strtoupper($proveedor->nombre) }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </td>

                            <td style="width: 5%;">
                                <button type="submit" class="btn btn-sm btn-success"
                                        @if(!comprobarPermisos('articulos.edit')) disabled @endif >
                                    <i class="fas fa-save"></i>
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>



            </div>

            <div class="modal-footer card-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" id="btn_modal_articulos_proveedores">Cerrar</button>
            </div>

            {!! verSpinner() !!}

            <div class="overlay-wrapper d-none cargar_articulos">
                <div class="overlay">
                    <div class="spinner-border text-navy" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
