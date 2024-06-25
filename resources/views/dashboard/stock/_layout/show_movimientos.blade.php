@if($almacenes_id)
    <div class="col-md-12" xmlns:wire="http://www.w3.org/1999/xhtml">
    <div class="card">

        <div class="card-header border-0">
            <h3 class="card-title text-uppercase">{{ $almacen->nombre }}</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" wire:click="setLimit" @if($rows > $rowsMovimientos) disabled @endif >
                    <i class="fas fa-sort-amount-down-alt"></i> Ver más
                </button>
                {{--<a href="{{ route('movimientos.reportes', [$getAlmacen ?? 0, $empresas_id ?? 1, $getLimit]) }}" class="btn btn-tool btn-sm" target="_blank">
                    <i class="fas fa-download"></i>
                </a>--}}
                <button type="button" class="btn btn-tool" data-card-widget="remove" wire:click="limpiarStock" {{--onclick="verSpinnerOculto()"--}}>
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <div class="card-body table-responsive p-0" @if($tableStyle) style="height: 60vh;" @endif>
            <table class="table table-head-fixed table-striped table-valign-middle table-sm">
                <thead>
                <tr>
                    <th class="d-none d-md-table-cell">Tipo</th>
                    <th class="d-none d-md-table-cell">Modulo</th>
                    <th class="d-none d-md-table-cell">Codigo</th>
                    <th class="d-none d-md-table-cell">Fecha</th>
                    <th>Articulo</th>
                    <th>Descripción</th>
                    {{--<th>Segmento</th>--}}
                    <th class="d-none d-md-table-cell text-right">Unidad</th>
                    <th class="text-right">Cantidad</th>
                    <th class="d-none d-md-table-cell text-center" style="width: 2%">Más</th>
                </tr>
                </thead>
                <tbody>
                @foreach($listarMovimientos as $movimientos)
                    @foreach($movimientos['detalles'] as $detalle)
                        <tr>
                        <td class="d-none d-md-table-cell">{{ $detalle['tipo'] }}</td>
                        <td class="d-none d-md-table-cell text-uppercase">{{ $movimientos['tabla'] }}</td>
                        <td class="d-none d-md-table-cell text-uppercase">{{ $movimientos['codigo'] }}</td>
                        <td class="d-none d-md-table-cell">{{--{{ diaEspanol($movimientos['fecha']) }}, --}}{{ verFecha($movimientos['fecha'], 'd/m/Y h:i:s a') }}</td>
                        <td class="text-uppercase">
                            <span class="d-none d-md-table-cell">{{ $detalle['codigo'] }}</span>
                        </td>
                        <td class="text-uppercase">
                            <span class="d-none d-md-table-cell">{{ $detalle['articulo'] }}</span>
                        </td>
                        {{--<td class="text-uppercase">{{ $movimientos['segmento'] }}</td>--}}
                        <td class="d-none d-md-table-cell text-right">{{ $detalle['unidad'] }}</td>
                        <td class="text-right">
                            <span class="text-nowrap">
                                @if($detalle['entrada'])
                                    <small class="text-success mr-1">
                                    <i class="fas fa-arrow-up"></i>
                                    </small>
                                @else
                                    <small class="text-danger mr-1">
                                    <i class="fas fa-arrow-down"></i>
                                    </small>
                                @endif
                                {{ formatoMillares($detalle['cantidad'], 3) }}
                            </span>
                        </td>
                        <td class="d-none d-md-table-cell text-center">
                            @if($movimientos['tabla'] == "ajustes")
                                <button type="button" class="btn btn-link text-muted" onclick="irAjuste()"
                                        wire:click="irAjuste({{ $movimientos['id'] }}, '{{ $movimientos['codigo'] }}')">
                                    <i class="fas fa-search"></i>
                                </button>
                            @else
                                <a href="{{ route('despachos.print', $movimientos['id']) }}" target="_blank"
                                   class="btn text-muted @if(!comprobarPermisos('despachos.print')) disabled @endif ">
                                    <i class="fas fa-print"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
        </div>

        {!! verSpinner() !!}

    </div>
</div>
@endif
