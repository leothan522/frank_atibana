<div wire:ignore.self class="modal fade" id="modal-lg-stock-nuevo" xmlns:wire="http://www.w3.org/1999/xhtml">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-uppercase">{{--<i class="fas fa-store-alt"></i>--}} {{ $modalEmpresa->nombre ?? '' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="card card-solid">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-8">

                                <h3 class="col-12 my-3 text-uppercase text-bold">{{ $modalArticulo->descripcion ?? '' }}</h3>

                                @if(!empty($modalArticulo) && !$modalArticulo->estatus)
                                    <label class="col-12 text-center">
                                        <span class="text-danger"><i class="fas fa-ban"></i></span>
                                        <span class="text-danger">Articulo Inactivo</span>
                                    </label>
                                @endif

                                <label class="col-12"><span
                                        class="text-muted">Codigo:</span>&nbsp;&nbsp;<span class="text-uppercase">{{ $modalArticulo->codigo ?? '' }}</span>
                                </label>
                                <label class="col-12"><span
                                        class="text-muted">Categoria:</span>&nbsp;&nbsp;<span class="text-uppercase">{{ $modalArticulo->categoria->nombre ?? '' }}</span>
                                </label>
                                <label class="col-12"><span
                                        class="text-muted">Unidad Mostrada:</span>&nbsp;&nbsp;<span class="text-uppercase">{{ $modalUnidad->nombre ?? '' }}</span>
                                </label>

                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-md-12"><span class="text-muted"><i class="fas fa-boxes"></i> Existencias:</span>
                                <span class="float-right text-bold text-uppercase">{{ $modalUnidad->codigo ?? '' }}</span>
                            </label>
                            <div class="table-responsive p-0">
                                <table class="table {{--table-head-fixed--}} table-hover text-nowrap">
                                    <thead>
                                    <tr class="text-navy">
                                        <th>Almacén</th>
                                        <th class="text-right">Actual</th>{{--
                                        <th class="text-right">Comprom.</th>
                                        <th class="text-right">Disponible</th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($modalStock)
                                        @php($total = 0)
                                        @foreach($modalStock as $stock)
                                            {{--@if($stock->actual <= 0)
                                                @continue
                                            @endif--}}
                                            <tr>
                                                <td>
                                                    <span class="text-uppercase">{{ $stock->almacen->nombre }}</span><br>
                                                    <em class="text-xs text-muted"><i class="fas fa-history"></i> {{ verFecha($stock->updated_at, 'd/m/Y h:t a') }}</em>
                                                </td>
                                                <td class="text-right">
                                                    {{ formatoMillares($stock->actual, 3) }}
                                                </td>{{--
                                                <td class="text-right">{{ formatoMillares($existencia->comprometido, 3) }}</td>
                                                <td class="text-right">{{ formatoMillares($existencia->disponible, 3) }}</td>--}}
                                            </tr>
                                            @php($total = $total + $stock->actual)
                                        @endforeach
                                        <tr>
                                            <th colspan="2" class="text-right"><span class="text-muted mr-3">Total:</span><span class="text-navy">{{ formatoMillares($total, 3) }}</span></th>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->
                </div>


            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default btn-sm" wire:click="verArticulo({{ $modalArticulo->id ?? 0 }}, {{ $modalUnidad->id ?? 0 }})">
                    <i class="fas fa-sync"></i> Actualizar
                </button>
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">{{ __('Close') }}</button>
            </div>

            {!! verSpinner() !!}

        </div>
    </div>
</div>
