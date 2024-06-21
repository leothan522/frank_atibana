{{--<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
    Launch Default Modal
</button>--}}

<div wire:ignore.self class="modal fade" id="modal-existencias" xmlns:wire="http://www.w3.org/1999/xhtml">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content {{--fondo--}}">
            <div class="modal-header bg-navy">
                <h4 class="modal-title">Existencias</h4>
                <button type="button" {{--wire:click="limpiar()"--}} class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body m-0">
                <div class="row table-responsive" style="height: 40vh;">
                    <table class="table table-sm table-head-fixed table-hover text-nowrap">
                        <thead>
                        <tr class="text-navy">
                            <th style="width: 5%">#</th>
                            <th>Articulo</th>
                            <th>Descripción</th>
                            <th style="width: 10%">Unidad</th>
                            <th style="width: 10%" class="text-right">Disponible</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($i = 0)
                        @foreach($listarExistencias as $articulo)
                            @php($i++)
                            <tr>
                                <td>{{ $i }}</td>
                                <td class="text-uppercase">{{ $articulo['codigo'] }}</td>
                                <td class="text-uppercase">{{ $articulo['descripcion'] }}</td>
                                <td class="text-right">{{ $articulo['unidad'] }}</td>
                                <td class="text-right">{{ formatoMillares($articulo['cantidad'], 3) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
            {!! verSpinner() !!}
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
