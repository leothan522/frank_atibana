<div wire:ignore.self class="modal fade" id="modal-sm-articulos-proveedores" xmlns:wire="http://www.w3.org/1999/xhtml">
    <div class="modal-dialog {{--modal-sm--}} modal-dialog-centered">
        <div class="modal-content">


            <div class="modal-header bg-navy">
                <h4 class="modal-title">Artículos Vinculados</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="text-white" aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="card-body p-0">

                <div class="table-responsive p-0" style="height: 40vh;">

                    <table class="table table-sm table-head-fixed table-hover text-nowrap">
                        <thead>
                        <tr class="text-navy">
                            <th style="width: 20%">Código</th>
                            <th>Descripción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($listarVinculados)
                            @foreach($listarVinculados as $vinculado)
                                <tr>
                                    <td class="text-uppercase">{{ $vinculado->articulo->codigo }}</td>
                                    <td class="text-uppercase">
                                        {{ $vinculado->articulo->descripcion }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2" class="text-center text-danger">
                                    Sin Artículos vinculados.
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>


                </div>


                <button type="button" class="m-2 btn btn-danger btn-sm" wire:click="desvincular"
                        @if(!comprobarPermisos('proveedores.edit')) disabled @endif >
                    Desvincular
                </button>

            </div>

            <div class="modal-footer card-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" id="cerrar_modal_proveedor">Cerrar</button>
            </div>

            {!! verSpinner() !!}

            <div class="overlay-wrapper d-none cargar_proveedores">
                <div class="overlay">
                    <div class="spinner-border text-navy" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
